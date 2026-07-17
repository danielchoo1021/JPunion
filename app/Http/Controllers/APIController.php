<?php
namespace App\Http\Controllers;

use App\ApiPartner;
use App\ApiPartnerAccess;
use App\Brand;
use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

use App\Transaction;
use App\TransactionDetail;
use App\TransactionPackage;

use App\Http\Controllers\GlobalController;
use App\Product;
use App\SettingUom;
use App\SubCategory;
use App\TransactionBillingAddress;
use Illuminate\Support\Facades\DB;

class APIController extends Controller
{
	public function request_transaction(Request $request)
	{
    
		try{
			$json = $request->json()->all();
			\DB::beginTransaction();

			if(empty($json['data']['order'])){
				throw new \Exception("Invalid Order JSON format");
			}			

			$transaction = New Transaction();
			$transaction->transaction_no = GlobalController::GenerateTransactionNo();
			$transaction->user_id = "AD000001";
			$transaction->weight = $json['data']['order']['total_weight'];
			$transaction->sub_total = $json['data']['order']['sub_total'];
			$transaction->shipping_fee = $json['data']['order']['shipping_fee'];
			$transaction->grand_total = $json['data']['order']['grand_total'];
			$transaction->address_name = $json['data']['order']['address_name'];
			$transaction->address = $json['data']['order']['address'];
			$transaction->postcode = $json['data']['order']['postcode'];
			$transaction->city = $json['data']['order']['city'];
			$transaction->state = $json['data']['order']['state'];
			$transaction->phone = $json['data']['order']['phone'];
			$transaction->email = $json['data']['order']['email'];
			$transaction->status = 1;
			$transaction->save();

			if(empty($json['data']['order']['details'])){
				throw new \Exception("Invalid Order Details JSON format");
			}

			// print_r($json['data']['order']['details'][0]['product_id']);
			foreach($json['data']['order']['details'] as $detail){
			$transaction_details = New TransactionDetail();
			$transaction_details->transaction_id = $transaction->id;
			$transaction_details->product_id = $detail['product_id'];
			$transaction_details->variation_id = $detail['variation_id'];
			$transaction_details->second_variation_id = $detail['second_variation_id'];
			$transaction_details->unit_weight = $detail['unit_weight'];
			$transaction_details->sub_category = $detail['variation_name'];
			$transaction_details->second_sub_category = $detail['second_variation_name'];
			$transaction_details->product_name = $detail['product_name'];
			$transaction_details->unit_price = $detail['unit_price'];
			$transaction_details->costing_price = $detail['costing_price'];
			$transaction_details->quantity = $detail['quantity'];
			$transaction_details->save();
			}


			\DB::commit();

			return "ok";

		} catch (\Exception $e){
            \DB::rollback();
            return $e->getMessage();
        } catch (\Error $e){
        	\DB::rollback();
            return $e->getMessage();
        }
	}

  public function authorizeAccount(Request $request){
		$request_params = $request->json()->all();
		$error = false;
		$errorCode = "";
		$errorMsg = "";
		$responseParams = [];
 
		if(!isset($request_params['partner_email']) || is_null($request_params['partner_email']) || trim($request_params['partner_email']) == ''){
			$error = true;
			$errorCode = "error_auth";
			$errorMsg = "Invalid partner_email";
		}

		if(!isset($request_params['partner_id']) || is_null($request_params['partner_id']) || trim($request_params['partner_id']) == ''){
			$error = true;
			$errorCode = "error_auth";
			$errorMsg = "Invalid partner_id";
		}

		if(!isset($request_params['sign']) || is_null($request_params['sign']) || trim($request_params['sign']) == ''){
			$error = true;
			$errorCode = "error_auth";
			$errorMsg = "Invalid sign";
		}

		if(!$error){
			$partner = ApiPartner::where([
				'partner_id' => $request_params['partner_id'],
				'partner_email' => $request_params['partner_email']
			])->first();

			if(!$partner){
				$error = true;
				$errorCode = "error_auth";
				$errorMsg = "Partner not found";
			} 

			if(!$error){
				$partnerKey = $partner->partner_key;
				// sign = partner_id + partner_email
				// encrypt = sha256 with partner key
				$encryptedSign = $this->encryptToken(($request_params['partner_id'].$request_params['partner_email']),$partnerKey);

				if($encryptedSign !== $request_params['sign']){
					$error = true;
					$errorCode = "error_auth";
					$errorMsg = "Invalid sign";
				}

				if(!$error){
					// Correct credential, generate access token and refresh token
					$accessToken = $this->encryptToken($encryptedSign, ('accesstoken'.time()));
					$refreshToken = $this->encryptToken($encryptedSign, ('refreshtoken'.time()));
					$accessTokenExpireAt = time() + 21600; // Access token expire 6 hours later

					$apiPartnerAccess = ApiPartnerAccess::where('api_partners_id', $partner->partner_id)->first();

					if(!$apiPartnerAccess){
						$saveToken = new ApiPartnerAccess();
	
						$saveToken->api_partners_id = $partner->partner_id;
						$saveToken->access_token = $accessToken;
						$saveToken->refresh_token = $refreshToken;
						$saveToken->expire_at = date("Y-m-d H:i:s", $accessTokenExpireAt);
						$saveToken->created_at = date("Y-m-d H:i:s");
						$saveToken->updated_at = date("Y-m-d H:i:s");
						$saveToken->save();
						// save as a new record in ApiPartnerAccess
					}
					else{
						$updateApiPartnerAccess = ApiPartnerAccess::where('api_partners_id', $partner->partner_id)
																			->update([
																				'api_partners_id' => $partner->partner_id,
																				'access_token' => $accessToken,
																				'refresh_token' => $refreshToken,
																				'expire_at' => date("Y-m-d H:i:s", $accessTokenExpireAt),
																				'created_at' => date("Y-m-d H:i:s"),
																				'updated_at' => date("Y-m-d H:i:s")
																			]);
						// update current ApiPartnerAccess record with the new access token / refresh token / expire at value
					}

					$errorCode = "";
					$errorMsg = "";
					$responseParams = [
						'access_token' => $accessToken,
						'refresh_token' => $refreshToken,
						'expire_at' => $accessTokenExpireAt
					];
				}
			}
		}

		return json_encode([
			'success' => (!$error) ? 'true' : 'false',
			'error_code' => $errorCode,
			'error_message' => $errorMsg,
			'response' => $responseParams 
		]);
	}

	public function refreshAccessToken(Request $request){
		$request_params = $request->json()->all();
		$error = false;
		$errorCode = "";
		$errorMsg = "";
		$responseParams = [];

		if(!isset($request_params['partner_email']) || is_null($request_params['partner_email']) || trim($request_params['partner_email']) == ''){
			$error = true;
			$errorCode = "error_auth";
			$errorMsg = "Invalid partner_email";
		}

		if(!isset($request_params['partner_id']) || is_null($request_params['partner_id']) || trim($request_params['partner_id']) == ''){
			$error = true;
			$errorCode = "error_auth";
			$errorMsg = "Invalid partner_id";
		}

		if(!isset($request_params['refresh_token']) || is_null($request_params['refresh_token']) || trim($request_params['refresh_token']) == ''){
			$error = true;
			$errorCode = "error_refresh_token";
			$errorMsg = "Invalid refresh_token";
		}

		if(!isset($request_params['sign']) || is_null($request_params['sign']) || trim($request_params['sign']) == ''){
			$error = true;
			$errorCode = "error_auth";
			$errorMsg = "Invalid sign";
		}

		if(!$error){
			// check refresh token belong to the same partner id
			$checkRefreshToken = ApiPartnerAccess::where([
				'api_partners_id' => $request_params['partner_id'],
				'refresh_token' => $request_params['refresh_token']
			])
			// ->where('expire_at', '>=', date('Y-m-d H:i:s'))
			->first();
			
			if(!$checkRefreshToken){
				$error = true;
				$errorCode = "error_refresh_token";
				$errorMsg = "Invalid refresh_token";
			}
			
			if(!$error){
				// check partner credential validity
				$partner = ApiPartner::where([
					'partner_id' => $request_params['partner_id'],
					'partner_email' => $request_params['partner_email']
				])->first();
	
				if(!$partner){
					$error = true;
					$errorCode = "error_auth";
					$errorMsg = "Partner not found";
				} 
	
				if(!$error){
					$partnerKey = $partner->partner_key;
					// sign = partner_id + partner_email + refresh_token
					// encrypt = sha256 with partner key
					$encryptedSign = $this->encryptToken(($request_params['partner_id'].$request_params['partner_email'].$request_params['refresh_token']),$partnerKey);
	
					if($encryptedSign !== $request_params['sign']){
						$error = true;
						$errorCode = "error_auth";
						$errorMsg = "Invalid sign";
					}
	
					if(!$error){
						// Correct credential, generate access token and refresh token
						$accessToken = $this->encryptToken($encryptedSign, ('accesstoken'.time()));
						$refreshToken = $this->encryptToken($encryptedSign, ('refreshtoken'.time()));
						$accessTokenExpireAt = time() + 21600; // Access token expire 6 hours later
	
						$updateApiPartnerAccess = ApiPartnerAccess::where('api_partners_id', $partner->partner_id)
																			->update([
																				'api_partners_id' => $partner->partner_id,
																				'access_token' => $accessToken,
																				'refresh_token' => $refreshToken,
																				'expire_at' => date("Y-m-d H:i:s", $accessTokenExpireAt),
																				'created_at' => date("Y-m-d H:i:s"),
																				'updated_at' => date("Y-m-d H:i:s")
																			]);
						// update current ApiPartnerAccess record with the new access token / refresh token / expire at value
						$errorCode = "";
						$errorMsg = "";
						$responseParams = [
							'access_token' => $accessToken,
							'refresh_token' => $refreshToken,
							'expire_at' => $accessTokenExpireAt
						];
					}
				}
			}
		}

		return json_encode([
			'success' => (!$error) ? 'true' : 'false',
			'error_code' => $errorCode,
			'error_message' => $errorMsg,
			'response' => $responseParams
		]);
	}

	public function getProductListing(Request $request){
		// params = partner_id, offset, limit, access_token, sign
		$request_params = $request->json()->all();
		$error = false;
		$errorCode = "";
		$errorMsg = "";
		$responseParams = [];
		
		if(!isset($request_params['partner_id']) || is_null($request_params['partner_id']) || trim($request_params['partner_id']) == ''){
			$error = true;
			$errorCode = "error_auth";
			$errorMsg = "Invalid partner_id";
		}

		if(!isset($request_params['access_token']) || is_null($request_params['access_token']) || trim($request_params['access_token']) == ''){
			$error = true;
			$errorCode = "error_access_token";
			$errorMsg = "Invalid access_token";
		}

		if(!isset($request_params['sign']) || is_null($request_params['sign']) || trim($request_params['sign']) == ''){
			$error = true;
			$errorCode = "error_auth";
			$errorMsg = "Invalid sign";
		}

		if(!isset($request_params['offset']) || is_null($request_params['offset']) || trim($request_params['offset']) == ''){
			$error = true;
			$errorCode = "error_param";
			$errorMsg = "Invalid offset";
		}

		if(!isset($request_params['page_size']) || is_null($request_params['page_size']) || trim($request_params['page_size']) == ''){
			$error = true;
			$errorCode = "error_param";
			$errorMsg = "Invalid page_size";
		}

		if(isset($request_params['page_size']) && $request_params['page_size'] > 100){
			$error = true;
			$errorCode = "error_param";
			$errorMsg = "page_size maximum up to 100 only ";
		}

		if(!$error){
			// check access token belong to the same partner id
			$checkAccessToken = ApiPartnerAccess::where([
				'api_partners_id' => $request_params['partner_id'],
				'access_token' => $request_params['access_token']
			])
			// ->where('expire_at', '>=', date('Y-m-d H:i:s'))
			->first();
			
			if(!$checkAccessToken){
				$error = true;
				$errorCode = "error_access_token";
				$errorMsg = "access_token_expired";
			}
			
			if(!$error){
				// check partner credential validity
				$partner = ApiPartner::where([
					'partner_id' => $request_params['partner_id'],
				])->first();
	
				if(!$partner){
					$error = true;
					$errorCode = "error_auth";
					$errorMsg = "Partner not found";
				} 
	
				if(!$error){
					$partnerKey = $partner->partner_key;
					// sign = partner_id + partner_email + path + access_token
					// encrypt = sha256 with partner key
					$encryptedSign = $this->encryptToken(($request_params['partner_id'].$partner->partner_email."/products/get".$request_params['access_token']),$partnerKey);
	
					if($encryptedSign !== $request_params['sign']){
						$error = true;
						$errorCode = "error_auth";
						$errorMsg = "Invalid sign";
					}
	
					if(!$error){
						// Correct credential, get product listing
						$products = Product::where('status','<>', 3)
						->offset((int)$request_params['offset'])
						->limit((int)$request_params['page_size'])
						->get();
						// response params
						/*
						
						*/
						$resultProducts = [];
						if($products){
							foreach($products as $i => $product){
								$category = Category::find($product['category_id']);
								$sub_category = SubCategory::find($product['sub_category_id']);
								$brands = Brand::find($product['brand_id']);
								$uom_type = SettingUom::find($product['product_type']);

								$resultProducts[$i]['id'] = $product['id'];
								$resultProducts[$i]['name'] = $product['product_name'];
								$resultProducts[$i]['costing_price'] = $product['costing_price'];
								$resultProducts[$i]['retail_price'] = $product['retail_price'];
								$resultProducts[$i]['retail_special_price'] = $product['retail_special_price'];
								$resultProducts[$i]['member_price'] = $product['price'];
								$resultProducts[$i]['member_special_price'] = $product['special_price'];
								$resultProducts[$i]['member_birthday_price'] = $product['birthday_price'];
								$resultProducts[$i]['member_birthday_special_price'] = $product['birthday_special_price'];
								$resultProducts[$i]['quantity'] = GlobalController::balance_quantity($product['id']); //
								$resultProducts[$i]['weight'] = $product['weight'];
								$resultProducts[$i]['category'] = ($category ? $category->category_name : ''); //
								$resultProducts[$i]['sub_category'] = ($sub_category ? $sub_category->sub_category_name : '');
								$resultProducts[$i]['product_sku'] = $product['product_code'];
								$resultProducts[$i]['brand'] = ($brands ? $brands->brand_name : '');
								$resultProducts[$i]['type'] = ($uom_type ? strtolower($uom_type->uom_name) : ''); // 
								$resultProducts[$i]['featured_product'] = $product['featured'];
								$resultProducts[$i]['display_on_website'] = $product['dow'];
								$resultProducts[$i]['free_shipping_west'] = (!is_null($product['free_west_shipping']) ? $product['free_west_shipping'] : "0");
								$resultProducts[$i]['free_shipping_east'] = (!is_null($product['free_east_shipping']) ? $product['free_east_shipping'] : "0");
								$resultProducts[$i]['agent_only'] = $product['agent_only'];
								$resultProducts[$i]['customer_only'] = $product['customer_only'];
								$resultProducts[$i]['store_stock'] = $product['store_stock'];
								$resultProducts[$i]['display_slider'] = $product['display_home_page_product_slider'];
								$resultProducts[$i]['item_code'] = $product['item_code'];
								$resultProducts[$i]['description'] = $product['description'];
								$resultProducts[$i]['testimonial'] = $product['testimonial'];
								$resultProducts[$i]['short_description'] = $product['short_description'];
								$resultProducts[$i]['get_point'] = $product['get_point'];
							}
						}

						$errorCode = "";
						$errorMsg = "";
						$responseParams['products'] = $resultProducts;
					}
				}
			}
		}

		return json_encode([
			'success' => (!$error) ? 'true' : 'false',
			'error_code' => $errorCode,
			'error_message' => $errorMsg,
			'response' => $responseParams
		]);
	}

	public function getTransactionListing(Request $request){
		// params = partner_id, offset, limit, access_token, sign
		$request_params = $request->json()->all();
		$error = false;
		$errorCode = "";
		$errorMsg = "";
		$responseParams = [];
		
		if(!isset($request_params['partner_id']) || is_null($request_params['partner_id']) || trim($request_params['partner_id']) == ''){
			$error = true;
			$errorCode = "error_auth";
			$errorMsg = "Invalid partner_id";
		}

		if(!isset($request_params['access_token']) || is_null($request_params['access_token']) || trim($request_params['access_token']) == ''){
			$error = true;
			$errorCode = "error_access_token";
			$errorMsg = "Invalid access_token";
		}

		if(!isset($request_params['sign']) || is_null($request_params['sign']) || trim($request_params['sign']) == ''){
			$error = true;
			$errorCode = "error_auth";
			$errorMsg = "Invalid sign";
		}

		if(!isset($request_params['offset']) || is_null($request_params['offset']) || trim($request_params['offset']) == ''){
			$error = true;
			$errorCode = "error_param";
			$errorMsg = "Invalid offset";
		}

		if(!isset($request_params['page_size']) || is_null($request_params['page_size']) || trim($request_params['page_size']) == ''){
			$error = true;
			$errorCode = "error_param";
			$errorMsg = "Invalid page_size";
		}

		if(isset($request_params['page_size']) && $request_params['page_size'] > 100){
			$error = true;
			$errorCode = "error_param";
			$errorMsg = "page_size maximum up to 100 only ";
		}

		if(!$error){
			// check access token belong to the same partner id
			$checkAccessToken = ApiPartnerAccess::where([
				'api_partners_id' => $request_params['partner_id'],
				'access_token' => $request_params['access_token']
			])
			// ->where('expire_at', '>=', date('Y-m-d H:i:s'))
			->first();
			
			if(!$checkAccessToken){
				$error = true;
				$errorCode = "error_access_token";
				$errorMsg = "access_token expired";
			}
			
			if(!$error){
				// check partner credential validity
				$partner = ApiPartner::where([
					'partner_id' => $request_params['partner_id'],
				])->first();
	
				if(!$partner){
					$error = true;
					$errorCode = "error_auth";
					$errorMsg = "Partner not found";
				} 
	
				if(!$error){
					$partnerKey = $partner->partner_key;
					// sign = partner_id + partner_email + path + access_token
					// encrypt = sha256 with partner key
					$encryptedSign = $this->encryptToken(($request_params['partner_id'].$partner->partner_email."/transactions/get".$request_params['access_token']),$partnerKey);
	
					if($encryptedSign !== $request_params['sign']){
						$error = true;
						$errorCode = "error_auth";
						$errorMsg = "Invalid sign";
					}
	
					if(!$error){
						$transactions = Transaction::select('transactions.*', 
																								DB::raw('COALESCE(COALESCE(ag.f_name, u.f_name), a.f_name) as customer_name'), 
																								DB::raw('COALESCE(COALESCE(ag.code, u.code), a.code) as customer_code'))
																			 ->join('transaction_details as d', 'd.transaction_id', 'transactions.id')
																			 ->leftJoin('agents as ag', 'ag.code', 'transactions.user_id')
																			 ->leftJoin('users as u', 'u.code', 'transactions.user_id')
																			 ->leftJoin('admins as a', 'a.code', 'transactions.user_id')
																			 ->where('transactions.status', '!=', '55')->get();
						$resultTransactions = [];
						if($transactions){
							foreach($transactions as $i => $transaction){
								$transactionStatus = "";
								if($transaction['status']){
									if($transaction['status'] == 99){
										$transactionStatus = 'Unpaid';
									}
									else if($transaction['status'] == 98){
										$transactionStatus = 'Waiting Verification';
									}
									else if($transaction['status'] == 97){
										$transactionStatus = 'In-progress';
									}
									else if($transaction['status'] == 96){
										$transactionStatus = 'Rejected';
									}
									else if($transaction['status'] == 1){
										if($transaction['completed'] == 1){
											$transactionStatus = 'Delivered';
										}
										else if($transaction['completed'] != 1 && $transaction['to_receive']){
											$transactionStatus = 'To Receive';
										}
										else{
											$transactionStatus = 'Paid';
										}
									}
									else{
										$transactionStatus = 'Cancelled';
									}
								}
								
								$resultTransactions[$i]['transaction_no'] = $transaction['transaction_no'];
								$resultTransactions[$i]['transaction_date'] = date("Y-m-d H:i:s",strtotime($transaction['created_at']));
								
								$resultTransactions[$i]['buyer_name'] = $transaction['customer_name'];
								
								$resultTransactions[$i]['paid_with_point'] = $transaction['pv_purchase'] ? 'true' : 'false';
								$resultTransactions[$i]['total_amount'] = number_format($transaction['grand_total'], 2);

								
								$resultTransactions[$i]['status'] = $transactionStatus;
								$resultTransactions[$i]['pick_up_method'] = $transaction['cod_address'] ? "Self Pickup" : "Courier Service";

								$resultTransactions[$i]['recipient'] = [
									'name' => $transaction['address_name'],
									'address' => $transaction['address'],
									'phone' => $transaction['phone']
								];

								if($transaction['different_billing_address']){
									$bill_address = TransactionBillingAddress::select('transaction_billing_addresses.*', 's.name as NameOfState')
                                                  ->leftJoin('states as s', 's.id', 'transaction_billing_addresses.state')
                                                  ->where('transaction_id', $transaction['id'])
                                                  ->first();

									$resultTransactions[$i]['billing'] = [
										'name' => $bill_address['address_name'],
										'address' => $bill_address['address'],
										'phone' => $bill_address['phone']
									];
								}
								else{
									$resultTransactions[$i]['billing'] = [
										'name' => $transaction['address_name'],
										'address' => $transaction['address'],
										'phone' => $transaction['phone']
									];
								}
							}
						}

						$errorCode = "";
						$errorMsg = "";
						$responseParams['transactions'] = $resultTransactions;
					}
				}
			}
		}

		return json_encode([
			'success' => (!$error) ? 'true' : 'false',
			'error_code' => $errorCode,
			'error_message' => $errorMsg,
			'response' => $responseParams
		]);
	}

	public function getTransactionPackage(Request $request, $transaction_no = ''){
		// params : partner_id, transaction_no, access_token, sign
		$request_params = $request->json()->all();
		$error = false;
		$errorCode = "";
		$errorMsg = "";
		$responseParams = [];
		
		if(!isset($request_params['partner_id']) || is_null($request_params['partner_id']) || trim($request_params['partner_id']) == ''){
			$error = true;
			$errorCode = "error_auth";
			$errorMsg = "Invalid partner_id";
		}

		if(!isset($request_params['access_token']) || is_null($request_params['access_token']) || trim($request_params['access_token']) == ''){
			$error = true;
			$errorCode = "error_access_token";
			$errorMsg = "Invalid access_token";
		}

		if(!isset($request_params['sign']) || is_null($request_params['sign']) || trim($request_params['sign']) == ''){
			$error = true;
			$errorCode = "error_auth";
			$errorMsg = "Invalid sign";
		}

		if(!isset($transaction_no) || is_null($transaction_no) || trim($transaction_no) == ''){
			$error = true;
			$errorCode = "error_param";
			$errorMsg = "Invalid transaction_no";
		}

		if(!$error){
			// check access token belong to the same partner id
			$checkAccessToken = ApiPartnerAccess::where([
				'api_partners_id' => $request_params['partner_id'],
				'access_token' => $request_params['access_token']
			])
			// ->where('expire_at', '>=', date('Y-m-d H:i:s'))
			->first();
			
			if(!$checkAccessToken){
				$error = true;
				$errorCode = "error_access_token";
				$errorMsg = "access_token expired";
			}
			
			if(!$error){
				// check partner credential validity
				$partner = ApiPartner::where([
					'partner_id' => $request_params['partner_id'],
				])->first();
	
				if(!$partner){
					$error = true;
					$errorCode = "error_auth";
					$errorMsg = "Partner not found";
				} 
	
				if(!$error){
					$partnerKey = $partner->partner_key;
					// sign = partner_id + partner_email + path + access_token
					// encrypt = sha256 with partner key
					$encryptedSign = $this->encryptToken(($request_params['partner_id'].$partner->partner_email."/transaction/package/get/".$transaction_no.$request_params['access_token']),$partnerKey);
	
					if($encryptedSign !== $request_params['sign']){
						$error = true;
						$errorCode = "error_auth";
						$errorMsg = "Invalid sign";
					}
	
					if(!$error){
						$transaction = Transaction::where('transaction_no', $transaction_no)->first();

						$resultTransactionPackage = [];
						if($transaction){
							$transactionPackages = TransactionDetail::where('transaction_id', $transaction->id)->get();

							$resultTransactionPackage['transaction_no'] = $transaction_no;
							if($transactionPackages){
								foreach($transactionPackages as $i => $transactionPackage){
									$resultTransactionPackage['packages'][$i] = [
										'name' => $transactionPackage['product_name'],
										'option' => $transactionPackage['sub_category'],
										'second_option' => $transactionPackage['second_sub_category'],
										'unit_price' => $transactionPackage['unit_price'],
										'total_price' => ($transactionPackage['unit_price'] * $transactionPackage['quantity']),
										'quantity' => $transactionPackage['quantity'],
										'total_weight' => ($transactionPackage['unit_weight'] * $transactionPackage['quantity'])
									];
								}
							}
						}

						$errorCode = "";
						$errorMsg = "";
						$responseParams = $resultTransactionPackage;
					}
				}
			}
		}

		return json_encode([
			'success' => (!$error) ? 'true' : 'false',
			'error_code' => $errorCode,
			'error_message' => $errorMsg,
			'response' => $responseParams
		]);
	}

  public function createTransaction(Request $request){
    $request_params = $request->json()->all();
    $requestPayload = !empty($request_params['payload']) ? $request_params['payload'] : ''; 
		$error = false;
		$errorCode = "";
		$errorMsg = "";
		$responseParams = [];

    if(!isset($request_params['partner_id']) || is_null($request_params['partner_id']) || trim($request_params['partner_id']) == ''){
			$error = true;
			$errorCode = "error_auth";
			$errorMsg = "Invalid partner_id";
		}

		if(!isset($request_params['access_token']) || is_null($request_params['access_token']) || trim($request_params['access_token']) == ''){
			$error = true;
			$errorCode = "error_access_token";
			$errorMsg = "Invalid access_token";
		}

		if(!isset($request_params['sign']) || is_null($request_params['sign']) || trim($request_params['sign']) == ''){
			$error = true;
			$errorCode = "error_auth";
			$errorMsg = "Invalid sign";
		}

    if(empty($requestPayload)){
      $error = true;
			$errorCode = "error_params";
			$errorMsg = "Invalid payload";
    }

    if(!$error){
			// check access token belong to the same partner id
			$checkAccessToken = ApiPartnerAccess::where([
				'api_partners_id' => $request_params['partner_id'],
				'access_token' => $request_params['access_token']
			])
			// ->where('expire_at', '>=', date('Y-m-d H:i:s'))
			->first();
			
			if(!$checkAccessToken){
				$error = true;
				$errorCode = "error_access_token";
				$errorMsg = "access_token expired";
			}
			
			if(!$error){
				// check partner credential validity
				$partner = ApiPartner::where([
					'partner_id' => $request_params['partner_id'],
				])->first();
	
				if(!$partner){
					$error = true;
					$errorCode = "error_auth";
					$errorMsg = "Partner not found";
				} 
	
				if(!$error){
					$partnerKey = $partner->partner_key;
					// sign = partner_id + partner_email + path + access_token
					// encrypt = sha256 with partner key
					$encryptedSign = $this->encryptToken(($request_params['partner_id'].$partner->partner_email."/transaction/add".$request_params['access_token']),$partnerKey);
	
					if($encryptedSign !== $request_params['sign']){
						$error = true;
						$errorCode = "error_auth";
						$errorMsg = "Invalid sign";
					}
	
					if(!$error){
            if(!isset($requestPayload['remark']) || empty($requestPayload['remark']) || !isset($requestPayload['discount_code']) || empty($requestPayload['discount_code']) || !isset($requestPayload['discount_amount']) || empty($requestPayload['discount_amount']) || !isset($requestPayload['shipping_fee']) || empty($requestPayload['shipping_fee'])){
              $error = true;
              $errorCode = "error_params";
              $errorMsg = "Required field is empty";
            } 
          }

          if(!$error){
            if(!isset($requestPayload['customer_name']) || empty($requestPayload['customer_name']) || !isset($requestPayload['shipping_address']) || empty($requestPayload['shipping_address']) || !isset($requestPayload['shipping_postcode']) || empty($requestPayload['shipping_postcode']) || !isset($requestPayload['shipping_city']) || empty($requestPayload['shipping_city']) || !isset($requestPayload['shipping_state']) || empty($requestPayload['shipping_state']) || !isset($requestPayload['country_code']) || empty($requestPayload['country_code']) || !isset($requestPayload['contact_number']) || empty($requestPayload['contact_number']) || !isset($requestPayload['email']) || empty($requestPayload['email'])){
              $error = true;
              $errorCode = "error_params";
              $errorMsg = "Shipping address fields is required";
            } 

            if($requestPayload['country_code'] !== "MY"){
              $error = true;
              $errorCode = "error_params";
              $errorMsg = "Shipping country only support MY";
            }
          }
            
          // if(!$error){
          //   if(!empty($requestPayload['billing_address'])){
          //     if(!isset($requestPayload['billing_customer_name']) || empty($requestPayload['billing_customer_name']) ||
          //     !isset($requestPayload['billing_address']) || empty($requestPayload['billing_address']) ||
          //     !isset($requestPayload['billing_postcode']) || empty($requestPayload['billing_postcode']) ||
          //     !isset($requestPayload['billing_city']) || empty($requestPayload['billing_city']) ||
          //     !isset($requestPayload['billing_state']) || empty($requestPayload['billing_state']) ||
          //     !isset($requestPayload['billing_country_code']) || empty($requestPayload['billing_country_code']) ||
          //     !isset($requestPayload['billing_contact_number']) || empty($requestPayload['billing_contact_number']) ||
          //     !isset($requestPayload['billing_email']) || empty($requestPayload['billing_email'])){
          //       $error = true;
          //       $errorCode = "error_params";
          //       $errorMsg = "Billing address fields is required";
          //     }
          //   }
          // }

          if(!$error){
            if(!isset($requestPayload['products']) || empty($requestPayload['products'])){
              $error = true;
              $errorCode = "error_params";
              $errorMsg = "Product cannot be empty";
            }
          }

          $totalWeight = 0;
          $subTotal = 0;
          
          if(!$error){
            if(!empty($requestPayload['products'])){
              foreach($requestPayload['products'] as $product){
                if($error){
                  break;
                }
                
                // Sum up transaction total weight;
                $totalWeight = !empty($product['unit_weight']) && !empty($product['quantity']) ? (floatval($totalWeight) + floatval($product['unit_weight'] * $product['quantity'])) : $totalWeight;
                $subTotal = !empty($product['unit_price']) && !empty($product['quantity']) ? (floatval($subTotal) + floatval($product['unit_price'] * $product['quantity'])) : $subTotal;
                
                if(!isset($product['product_name']) || empty($product['product_name']) ||
                !isset($product['unit_weight']) || empty($product['unit_weight']) ||
                !isset($product['unit_price']) || empty($product['unit_price']) ||
                !isset($product['quantity']) || empty($product['quantity'])){
                  $error = true;
                  $errorCode = "error_params";
                  $errorMsg = "Product fields cannot be empty"; 
                }
              }
            }
          }

          // Proceed if validation no error
          if(!$error){
            $shippingFee = !empty($requestPayload['shipping_fee']) ? $requestPayload['shipping_fee'] : 0;
            $discountAmount = !empty($requestPayload['discount_amount']) ? $requestPayload['discount_amount'] : 0;
            $grandTotal = floatval($subTotal) + floatval($shippingFee) - floatval($discountAmount);
            $contactNumber = !empty($requestPayload['contact_number']) ? $requestPayload['contact_number'] : "";
            
            if(!empty($contactNumber)){
              $contactNumber = ltrim($contactNumber, "60");
            }

            $transaction_no = GlobalController::GenerateTransactionNo();

            $transaction = new Transaction();

            // $transaction->user_id = {Get from user table based on phone number / email};
            $transaction->transaction_no = $transaction_no;
            $transaction->weight = $totalWeight;
            $transaction->sub_total = $subTotal;
            $transaction->discount_code = !empty($requestPayload['discount_code']) ? $requestPayload['discount_code'] : "";
            $transaction->discount = floatval($discountAmount);
            $transaction->shipping_fee = floatval($shippingFee);
            $transaction->grand_total = floatval($grandTotal);
            $transaction->address_name = !empty($requestPayload['customer_name']) ? $requestPayload['customer_name'] : "";
            $transaction->address = !empty($requestPayload['shipping_address']) ? $requestPayload['shipping_address'] : "";
            $transaction->postcode = !empty($requestPayload['shipping_postcode']) ? $requestPayload['shipping_postcode'] : "";
            $transaction->city = !empty($requestPayload['shipping_city']) ? $requestPayload['shipping_city'] : "";
            $transaction->state = !empty($requestPayload['shipping_state']) ? $requestPayload['shipping_state'] : "";
            $transaction->phone = $contactNumber;
            $transaction->email = !empty($requestPayload['email']) ? $requestPayload['email'] : "";
            $transaction->country = 160;
            $transaction->remark = !empty($requestPayload['remark']) ? $requestPayload['remark'] : "";
            $transaction->status = 98;
            
            $transaction->save();

            if($transaction && !empty($requestPayload['products'])){
              foreach($requestPayload['products'] as $product){
                if($error){
                  break;
                }
               
                $transaction_detail = new TransactionDetail();

                $transaction_detail->transaction_id = $transaction->id;
                $transaction_detail->product_id = 0;
                $transaction_detail->variation_id = 0;
                $transaction_detail->second_variation_id = 0;
                $transaction_detail->item_code = !empty($product['item_code']) ? $product['item_code'] : "";
                $transaction_detail->unit_weight = !empty($product['unit_weight']) ? $product['unit_weight'] : "";
                $transaction_detail->product_name = !empty($product['product_name']) ? $product['product_name'] : "";
                $transaction_detail->unit_price = !empty($product['unit_price']) ? $product['unit_price'] : "";
                $transaction_detail->quantity = !empty($product['quantity']) ? $product['quantity'] : "";
                $transaction_detail->status = 1;

                $transaction_detail->save();
                
              }
            }

            $responseParams = [
              'transaction_number' => $transaction_no,
            ];
          }
				}
			}
		}

    return json_encode([
      'success' => (!$error) ? "true" : "false",
      'error_code' => $errorCode,
      'error_message' => $errorMsg,
      'response' => (!$error) ? $responseParams : []
    ]);
  }

  public function updateTransactionStatus(Request $request){
    $request_params = $request->json()->all();
    $requestPayload = !empty($request_params['payload']) ? $request_params['payload'] : ''; 
		$error = false;
		$errorCode = "";
		$errorMsg = "";
		$responseParams = [];

    if(!isset($request_params['partner_id']) || is_null($request_params['partner_id']) || trim($request_params['partner_id']) == ''){
			$error = true;
			$errorCode = "error_auth";
			$errorMsg = "Invalid partner_id";
		}

		if(!isset($request_params['access_token']) || is_null($request_params['access_token']) || trim($request_params['access_token']) == ''){
			$error = true;
			$errorCode = "error_access_token";
			$errorMsg = "Invalid access_token";
		}

		if(!isset($request_params['sign']) || is_null($request_params['sign']) || trim($request_params['sign']) == ''){
			$error = true;
			$errorCode = "error_auth";
			$errorMsg = "Invalid sign";
		}

    if(!$error){
      $partner = ApiPartner::where([
        'partner_id' => $request_params['partner_id'],
      ])->first();
  
      if(!$partner){
        $error = true;
        $errorCode = "error_auth";
        $errorMsg = "Partner not found";
      } 
  
      $partnerKey = $partner->partner_key;
      // sign = partner_id + partner_email + path + access_token
      // encrypt = sha256 with partner key
      $encryptedSign = $this->encryptToken(($request_params['partner_id'].$partner->partner_email."/transaction/updateStatus".$request_params['access_token']),$partnerKey);
  
      if($encryptedSign !== $request_params['sign']){
        $error = true;
        $errorCode = "error_auth";
        $errorMsg = "Invalid sign";
      }
    }
    

    if(empty($requestPayload)){
      $error = true;
			$errorCode = "error_params";
			$errorMsg = "Invalid payload";
    }

    // proceed update transaction status
    if(!$error){
      
      if(empty($requestPayload['transaction_number'])){
        $error = true;
        $errorCode = "error_params";
        $errorMsg = "Invalid transaction_number";
      }
    }

    if(!$error){
      
      if(empty($requestPayload['status_code'])){
        $error = true;
        $errorCode = "error_params";
        $errorMsg = "status_code cannot empty";
      }

      if($requestPayload['status_code'] !== 1 && $requestPayload['status_code'] !== 95){
        $error = true;
        $errorCode = "error_params";
        $errorMsg = "Invalid status_code";
      }
    }

    if(!$error){
      $transaction = Transaction::where('transaction_no', $requestPayload['transaction_number'])->first();

      if(!$transaction){
        $error = true;
        $errorCode = "error_params";
        $errorMsg = "Transaction not found";
      }
      else{
        $transaction->status = $requestPayload['status_code'];
        $transaction->save();
      }
    }

    return json_encode([
      'success' => (!$error) ? "true" : "false",
      'error_code' => $errorCode,
      'error_message' => $errorMsg,
      'response' => (!$error) ? $responseParams : []
    ]);
  }

	private function encryptToken($data, $key){
		// common function to encrypt token

		$encrypted_data = hash_hmac('sha256', $data, $key);
		return $encrypted_data;
	}
}
?>