<?php
namespace App\Http\Controllers\API;

use App\Agent;
use App\ApiEInvoice;
use App\Http\Controllers\Controller;
use App\SettingEinvoice;
use App\State;
use App\TblCountry;
use App\Transaction;
use App\TransactionDetail;
use App\TransactionEinvoice;
use App\User;
use DateTime;
use DateTimeZone;
use Illuminate\Support\Facades\Log;

class EinvoiceController extends Controller
{

  protected $clientId;
  protected $clientSecret;
  protected $clientSecret_1;
  protected $baseUrl;

  public function __construct($clientId, $clientSecret)
  {
    $this->baseUrl = "https://preprod-api.myinvois.hasil.gov.my";
    // $this->baseUrl = "https://api.myinvois.hasil.gov.my";
    $this->clientId = $clientId;
    $this->clientSecret = $clientSecret;
  }

  public function loginTaxPayer(){
    // Get access token or refresh token
    $checkAccessToken = ApiEInvoice::where('client_id', $this->clientId)->first();

    $requestNewToken = true;
    $accessToken = null;
    if($checkAccessToken){
      $expiryTime = strtotime($checkAccessToken->token_expiry);
      $currentTime = time();
      
      if($currentTime < $expiryTime){
        $requestNewToken = false;
        $accessToken = $checkAccessToken->access_token;
      }
    }

    if($requestNewToken){
      $apiUrl = "/connect/token";

      $body = "client_id=" . $this->clientId . "&client_secret=" . $this->clientSecret . "&grant_type=client_credentials&scope=InvoicingAPI";

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $this->baseUrl.$apiUrl);
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
      curl_setopt($ch, CURLOPT_HEADER, 0);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
      curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/x-www-form-urlencoded'
      ]);

      ob_start(); 
      $return = curl_exec($ch);
      ob_end_clean();
      curl_close($ch);

      $json = json_decode($return);
      
      if(!empty($json->access_token)){
        $getRecord = ApiEInvoice::where('client_id', $this->clientId)->first();

        if($getRecord){
          $update = ApiEInvoice::where('client_id', $this->clientId)->update([
            'access_token' => $json->access_token,
            'token_expiry' => date('Y-m-d H:i:s', strtotime("+".($json->expires_in - 60)." seconds")),
            'token_type' => $json->token_type,
            'token_scope' => $json->scope
          ]);
        }
        else{
          $newAccessToken = new ApiEInvoice();

          $newAccessToken->name = "E-Invoice";
          $newAccessToken->client_id = $this->clientId;
          $newAccessToken->client_secret_1 = $this->clientSecret;
          $newAccessToken->access_token = $json->access_token;
          $newAccessToken->token_expiry = date('Y-m-d H:i:s', strtotime("+".($json->expires_in - 60)." seconds"));
          $newAccessToken->token_type = $json->token_type;
          $newAccessToken->token_scope = $json->scope;

          $newAccessToken->save();
        }

        $accessToken = $json->access_token;
      }
    }
   
    if(!empty($accessToken)){
      return [
        'access_token' => $accessToken,
        'tin' => $checkAccessToken ? $checkAccessToken->tin : null,
        'id_type' => $checkAccessToken ? $checkAccessToken->id_type : null,
        'id_value' => $checkAccessToken ? $checkAccessToken->id_value : null,
      ];
    }
    else{
      return false;
    }
  }
  
  public function submitDocument($transactionNumber){
    $eInvoiceSetting = SettingEinvoice::where('status', 1)->first();
    if(!$eInvoiceSetting){
      return "Please enable e-Invoice API";
    }
    $accessToken = $this->loginTaxPayer();
    
    if($accessToken){
      $getTransaction = Transaction::where('transaction_no', $transactionNumber)->first();
      if(!$getTransaction){
        return [
          'status' => 'error',
          'message' => 'Transaction not found'
        ];
      }

      $getTransactionState= State::find($getTransaction->state);
      $getTransactionCountry = TblCountry::where('country_id', $getTransaction->country)->first();
      $transactionStateCode = $getTransactionState ? $getTransactionState->e_invoice_code : "NA";
      $transactionCountryCode = $getTransactionCountry ? $getTransactionCountry->e_invoice_code : "MYS";
      // Buyer Data
      $getBuyer = User::where('code', $getTransaction->user_id)->first();
      if(!$getBuyer){
        $getBuyer = Agent::where('code', $getTransaction->user_id)->first();
      }
      
      if(!$getBuyer){
        return [
          'status' => 'error',
          'message' => 'Buyer not found'
        ];
      }

      $getBuyerTin = "";
      if($getBuyer->ic){
        $getBuyerTin = $this->retrieveUserTin("NRIC", $getBuyer->ic);
      }

      $buyerAddrAddressArray = [];
      $buyerAddrAddressArray[] = [
        'Line' => [
          [
            '_' => substr($getTransaction->address, 0, 150)
          ]
        ]
      ];

      if(strlen($getTransaction->address) > 150){
        $buyerAddrAddressArray[] = [
          'Line' => [
            '_' => substr($getTransaction->address, 151, 150)
          ]
        ];
      }

      $buyerData = [];
      if($getBuyer){
        $buyerData = [
          'name' => $getBuyer->f_name,
          'tin' => $getBuyerTin,
          'email' => $getBuyer->email,
          'phone' => $getBuyer->phone,
        ];
      }

      $apiUrl = "/api/v1.0/documentsubmissions";
      // Start Supplier Parameters
      $supplierParameters = [
        [
          'Party' => [
            [
              'IndustryClassificationCode' => [
                [
                  '_' => (string)$eInvoiceSetting->industry_classification_code,
                  'name' => (string)$eInvoiceSetting->industry_classification_desc,
                ]
              ],
              'PartyIdentification' => [
                ['ID' => [
                  [
                    '_' => (string)$eInvoiceSetting->supplier_nric,
                    'schemeID' => "NRIC"
                  ]
                ]],
                ['ID' => [
                  [
                    '_' => (string)$eInvoiceSetting->supplier_tin,
                    'schemeID' => "TIN"
                  ]
                ]],
                ['ID' => [
                  [
                    '_' => "NA",
                    'schemeID' => "SST"
                  ]
                ]],
                ['ID' => [
                  [
                    '_' => "NA",
                    'schemeID' => "TTX"
                  ]
                ]]
              ],
              'PostalAddress' => [
                [
                  'CityName' => [
                    [
                      "_" => (string)$eInvoiceSetting->city_name
                    ]
                  ],
                  'PostalZone' => [
                    [
                      "_" => (string)$eInvoiceSetting->postal_code
                    ]
                  ],
                  'CountrySubentityCode' => [
                    [
                      "_" => (string)$eInvoiceSetting->state_code
                    ]
                  ],
                  'Country' => [
                    [
                      "IdentificationCode" => [
                        [
                          '_' => (string)$eInvoiceSetting->country_code,
                          'listID' => "ISO3166-1",
                          'listAgencyID' => "6"
                        ]
                      ]
                    ]
                  ],
                  'AddressLine' => [
                    [
                      'Line' => [
                        [
                          '_' => (string)$eInvoiceSetting->address_1
                        ]
                      ]
                    ],
                    [
                      'Line' => [
                        [
                          '_' => (string)$eInvoiceSetting->address_2
                        ]
                      ]
                    ],
                    [
                      'Line' => [
                        [
                          '_' => (string)$eInvoiceSetting->address_3
                        ]
                      ]
                    ]
                  ]
                ]
              ],
              'PartyLegalEntity' => [
                [
                  'RegistrationName' => [
                    [
                      "_" => (string)$eInvoiceSetting->supplier_name
                    ]
                  ]
                ]
              ],
              'Contact' => [
                [
                  'Telephone' => [
                    [
                      '_' => (string)$eInvoiceSetting->supplier_telephone
                    ]
                  ],
                  'ElectronicMail' => [
                    [
                      '_' => (string)$eInvoiceSetting->supplier_email
                    ]
                  ]
                ]
              ]
            ]
          ]
        ]
      ];
      // End Supplier Parameters
      // Start Buyer Parameters
      $buyerParameters = [
        [
          'Party' => [
            [
              'PostalAddress' => [
                [
                  'CityName' => [
                    [
                      '_' => $getTransaction->city
                    ]
                  ],
                  'PostalZone' => [
                    [
                      '_' => $getTransaction->postcode
                    ]
                  ],
                  'CountrySubentityCode' => [
                    [
                      '_' => $transactionStateCode
                    ]
                  ],
                  'AddressLine' => $buyerAddrAddressArray,
                  'Country' => [
                    [
                      'IdentificationCode' => [
                        [
                          '_' => $transactionCountryCode,
                          "listID" => "ISO3166-1",
                          "listAgencyID" => "6"
                        ]
                      ]
                    ]
                  ]
                ]
              ],
              'PartyLegalEntity' => [
                [
                  'RegistrationName' => [
                    [
                      '_' => "Test"
                    ]
                  ],
                ]
              ],
              'PartyIdentification' => [
                ['ID' => [
                  [
                    '_' => "NA",
                    'schemeID' => "BRN"
                  ]
                ]],
                ['ID' => [
                  [
                    '_' => !empty($buyerData['tin']) ? $buyerData['tin'] : "NA",
                    'schemeID' => "TIN"
                  ]
                ]],
                ['ID' => [
                  [
                    '_' => "NA",
                    'schemeID' => "SST"
                  ]
                ]],
              ],
              'Contact' => [
                [
                  'Telephone' => [
                    [
                      '_' => $buyerData['phone']
                    ]
                  ],
                  'ElectronicMail' => [
                    [
                      '_' => $buyerData['email']
                    ]
                  ]
                ]
              ]
            ]
          ],
        ]
      ];
      // End Buyer Parameters
      // Invoice Line Item Parameters
      $orderProducts = TransactionDetail::where('transaction_id', $getTransaction->id)->get();
      if($orderProducts->isEmpty()){
        return [
          'status' => 'error',
          'message' => 'Order products not found'
        ];
      }
      // Start Invoice Line Item Parameters
      $invoiceLineItemParameters = [];
      foreach($orderProducts as $index => $orderProduct){
        $productPrice = number_format(floatval(str_replace(",", "",  ($orderProduct->unit_price * $orderProduct->quantity))),2);
        
        $invoiceLineItemParameters[] = [
          'ID' => [
            [
              '_' => (string)($index + 1),
            ]
          ],
          'InvoicedQuantity' => [
            [
              '_' => (int)$orderProduct->quantity,
              'unitCode' => "C62",
            ]
          ],
          'LineExtensionAmount' => [
            [
              '_' => (float)number_format(floatval(str_replace(",", "",  ($productPrice))),2),
              'currencyID' => "MYR",
            ]
          ],
          'TaxTotal' => [
            [
              'TaxAmount' => [
                [
                  '_' => 0, 
                  'currencyID' => "MYR",
                ]
              ],
              'TaxSubtotal' => [
                [
                  'TaxableAmount' => [
                    [
                      '_' => 0,
                      'currencyID' => "MYR",
                    ]
                  ],
                  'TaxAmount' => [
                    [
                      '_' => 0,
                      'currencyID' => "MYR",
                    ]
                  ],
                  'Percent' => [
                    [
                      '_' => 0,
                    ]
                  ],
                  'TaxCategory' => [
                    [
                      'ID' => [
                        [
                          '_' => "E"
                        ]
                      ],
                      "TaxExemptionReason" => [
                        [
                          '_' => "Tax Exemption Reason",
                        ]
                      ],
                      'TaxScheme' => [
                        [
                          'ID' => [
                            [
                              '_' => "OTH",
                              'schemeID' => "UN/ECE 5153",
                              'schemeAgencyID' => "6",
                            ]
                          ],
                        ]
                      ]
                    ]
                  ]
                ]
              ]
            ]
          ],
          'Item' => [
            [
              'CommodityClassification' => [
                [
                  'ItemClassificationCode' => [
                    [
                      '_' => '003',
                      'listID' => 'CLASS',
                    ]
                  ]
                ]
              ],
              'Description' => [
                [
                  '_' => $orderProduct->product_name,
                ]
              ],
              'OriginCountry' => [
                [
                  'IdentificationCode' => [
                    [
                      '_' => "MYS"
                    ]
                  ]
                ]
              ],
            ]
          ],
          'Price' => [
            [
              'PriceAmount' => [
                [
                  '_' => (float)number_format(floatval(str_replace(",", "",  ($orderProduct->unit_price))),2),
                  'currencyID' => "MYR",
                ]
              ]
            ]
          ],
          'ItemPriceExtension' => [
            [
              'Amount' => [
                [
                  '_' => (float)number_format(floatval(str_replace(",", "",  ($orderProduct->unit_price))),2),
                  'currencyID' => "MYR",
                ]
              ]
            ]
          ]
        ];
      }
      // End Invoice Line Item Parameters
      $taxTotalParameters = [
        [
          'TaxAmount' => [
            [
              '_' => !empty($getTransaction->tax) ? $getTransaction->tax : 0,
              'currencyID' => 'MYR'
            ]
          ],
          'TaxSubtotal' => [
            [
              "TaxableAmount" => [
                [
                  "_" => !empty($getTransaction->tax) ? $getTransaction->tax : 0,
                  "currencyID" => "MYR"
                ]
              ],
              "TaxAmount" => [
                [
                  "_" => !empty($getTransaction->tax) ? $getTransaction->tax : 0,
                  "currencyID" => "MYR"
                ]
              ],
              "TaxCategory" => [
                [
                  "ID" => [
                    [
                      "_" => "01"
                    ]
                  ],
                  "TaxScheme" => [
                    [
                      "ID" => [
                        [
                          "_" => "OTH",
                          "schemeID" => "UN/ECE 5153",
                          "schemeAgencyID" => "6"
                        ]
                      ]
                    ]
                  ]
                ]
              ]
            ]
          ]
        ]
      ];
      
      // LegalMonetaryTotal
      $legalMonetaryParameters = [
        [
          "LineExtensionAmount" => [
            [
              "_" => $getTransaction->grand_total,
              "currencyID" => "MYR"
            ]
          ],
          "TaxExclusiveAmount" => [
            [
              "_" => ($getTransaction->tax ? $getTransaction->grand_total - $getTransaction->tax : $getTransaction->grand_total), // if got tax then need to exclude tax amount from here
              "currencyID" => "MYR"
            ]
          ],
          "TaxInclusiveAmount" => [
            [
              "_" => $getTransaction->grand_total, // Amount include tax 
              "currencyID" => "MYR"
            ]
          ],
          "AllowanceTotalAmount" => [
            [
              "_" => ($getTransaction->discount ? $getTransaction->discount : 0),
              "currencyID" => "MYR"
            ]
          ],
          "ChargeTotalAmount" => [
            [
              "_" => 0,
              "currencyID" => "MYR"
            ]
          ],
          "PayableRoundingAmount" => [
            [
              "_" => 0,
              "currencyID" => "MYR"
            ]
          ],
          "PayableAmount" => [
            [
              "_" => $getTransaction->grand_total,
              "currencyID" => "MYR"
            ]
          ]
        ]
      ];
      // Core Data Parameter
      $transactionDatetime = explode(" ", date("Y-m-d H:i", strtotime("-1 day", strtotime($getTransaction->created_at))));
      $documentParams = [
        '_D' => "urn:oasis:names:specification:ubl:schema:xsd:Invoice-2",
        '_A' => "urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2",
        '_B' => "urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2",
        'Invoice' => [
          [
            'ID' => [
              [
                '_' => $getTransaction->transaction_no,
              ]
            ],
            'IssueDate' => [
              [
                '_' => $transactionDatetime[0],
              ]
            ],
            'IssueTime' => [
              [
                '_' => $transactionDatetime[1]. ":00Z"
              ]
            ],
            'InvoiceTypeCode' => [
              [
                '_' => "01", 
                'listVersionID' => "1.0"
              ]
            ],
            'DocumentCurrencyCode' => [
              [
                '_' => "MYR"
              ]
            ],
            'TaxCurrencyCode' => [
              [
                '_' => "MYR"
              ]
            ],
            'InvoicePeriod' => [
              [
                'StartDate' => [
                  [
                    '_' => "NA"
                  ]
                ],
                'EndDate' => [
                  [
                    '_' => "NA"
                  ]
                ],
                'Description' => [
                  [
                    '_' => "NA" 
                  ]
                ]
              ]
            ],
            'BillingReference' => [
              [
                'AdditionalDocumentReference' => [
                  [
                    'ID' => [
                      [
                        "_" => $getTransaction->transaction_no
                      ]
                    ]
                  ]
                ]
              ]
            ],
            'AccountingSupplierParty' => $supplierParameters,
            'AccountingCustomerParty' => $buyerParameters,
            'InvoiceLine' => $invoiceLineItemParameters,
            'TaxTotal' => $taxTotalParameters,
            'LegalMonetaryTotal' => $legalMonetaryParameters,
          ]
        ]
      ];
      // End Core Data Parameter
      // echo '<pre>';print_r($documentParams);echo '</pre>';
      // echo json_encode($documentParams, true);
      $jsonDocumentData = json_encode($documentParams, true);
      $documentData = base64_encode($jsonDocumentData);
      $documentHash = hash("sha256",$jsonDocumentData);
      $apiBody = [
        'documents' => [['format' => "JSON",'documentHash' => $documentHash,'codeNumber' => $getTransaction->transaction_no,'document' => $documentData]]
      ];
      
      // echo "<br><br>---Access Token---<br><br>";
      // echo '<pre>';print_r($accessToken);echo '</pre>';
      // echo "<br><br>---Body---<br><br>";
      // echo json_encode($apiBody, true);
      // echo "<br><br>---End Body---<br><br>";
      // exit;
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $this->baseUrl.$apiUrl);
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($apiBody, true));
      curl_setopt($ch, CURLOPT_HEADER, 0);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
      curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer '.$accessToken['access_token']
      ]);

      ob_start(); 
      $return = curl_exec($ch);
      
      ob_end_clean();
      curl_close($ch);
      // echo "Result: <br>";
      // echo '<pre>';print_r(json_decode($return, true));echo '</pre>';exit;
      $result = json_decode($return, true);
      
      $transactionEinvoice = TransactionEinvoice::where('transaction_no', $getTransaction->transaction_no)->first();
      
      if(!$transactionEinvoice){
        $transactionEinvoice = new TransactionEinvoice();
        $transactionEinvoice->transaction_no = $getTransaction->transaction_no;
      }

      $success = true;
      if(!empty($result['acceptedDocuments']) && isset($result['acceptedDocuments'][0]['uuid'])){
        $transactionEinvoice->einvoice_uuid = $result['acceptedDocuments'][0]['uuid'];
        $transactionEinvoice->submission_uid = $result['submissionUid'];
        $transactionEinvoice->status = "success";
        $transactionEinvoice->api_response = "";
      }
      else if(!empty($result['rejectedDocuments']) && isset($result['rejectedDocuments'][0]['error'])){
        $transactionEinvoice->status = "error";
        $transactionEinvoice->api_response = json_encode($result['rejectedDocuments'][0]['error']);
        $success = false;
      }

      $transactionEinvoice->save();

      return [
        'status' => $success ? "success" : "error",
        'message' => $success ? "" : "API Error"
      ];
    }
    else{
      return [
        'status' => "error",
        'message' => "Token API Error"
      ];
    }
  }

  public function retrieveUserTin($userIdType, $userIdValue){
    $accessToken = $this->loginTaxPayer();

    if($accessToken){
      $apiUrl = "/api/v1.0/taxpayer/search/tin?idType=" . $userIdType . "&idValue=" . $userIdValue;

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $this->baseUrl.$apiUrl);
      curl_setopt($ch, CURLOPT_POST, 0);
      // curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
      curl_setopt($ch, CURLOPT_HEADER, 0);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
      curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $accessToken['access_token']
      ]);

      ob_start(); 
      $return = curl_exec($ch);
      ob_end_clean();
      curl_close($ch);
      
      $result = json_decode($return, true);

      return $result['tin'] ?? null;
    }

    return false;
  }

  public function getSubmission($einvoiceUuid){
    // Check e-Invoice document submission status
    $accessToken = $this->loginTaxPayer();

    if($accessToken){
      $apiUrl = "/api/v1.0/documentsubmissions/".$einvoiceUuid."?pageNo=1&pageSize=20";

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $this->baseUrl.$apiUrl);
      curl_setopt($ch, CURLOPT_POST, 0);
      // curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
      curl_setopt($ch, CURLOPT_HEADER, 0);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
      curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $accessToken['access_token']
      ]);

      ob_start(); 
      $return = curl_exec($ch);
      ob_end_clean();
      curl_close($ch);
      
      $result = json_decode($return, true);
      // echo '<pre>';print_r($result);echo '</pre>';exit;

      return $result;
      
    }

    return false;
  }
}
?>