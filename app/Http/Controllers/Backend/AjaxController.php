<?php

namespace App\Http\Controllers\Backend;

use App\Services\OrderPrintService;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\ProductImage;
use App\CodAddress;
use App\SettingRefferalReward;
use App\Cart;
use App\Merchant;
use App\SettingTeamDividend;
use App\SettingTopup;
use App\Admin;
use App\Affiliate;
use App\AgentLevel;
use App\SettingMerchantBonus;
use App\AgentRebateHistory;
use App\AffiliateCommission;
use App\Permission;
use App\Transaction;
use App\WithdrawalTransaction;
use App\Category;
use App\Brand;
use App\Product;
use App\Promotion;
use App\SettingMerchantCommission;
use App\SettingMerchantRebate;
use App\CategoryImage;
use App\TransactionDetail;
use App\User;
use App\SettingBanner;
use App\SubCategory;
use App\SettingDualMain;
use App\SettingDualCommission;
use App\AffiliateDual;
use App\TopupTransaction;
use App\SettingShippingFee;
use App\SettingPickUpAddress;
use App\SettingMainPage;
use App\ProductVariation;
use App\ProductSecondVariation;
use App\Staff;
use App\TransactionTracking;
use App\UserShippingAddress;
use App\BankAccount;
use App\Bundle;
use App\SettingSignatureDish;
use App\AgentLevelRecord;
use App\PaymentBank;
use App\PickupContact;
use App\SettingDownloadMaterial;
use App\SettingCommission;
use App\AppliedPromotion;
use App\Corporate;
use App\AgentPrice;
use App\PartnerLevel;
use App\AreaAgentLevel;
use App\City;
use App\State;
use App\WebsiteSetting;
use App\TestimonialList;
use App\SalesPopup;
use App\MemberPv;
use App\SettingFeedbackDetail;
use App\SettingFeedback;
use App\SettingUom;
use App\TransactionPackage;
use App\JoiningRecord;
use App\SettingRetailCommission;
use App\PromoAgentItem;
use App\PromoAgentItemDetail;
use App\WithdrawalStock;
use App\SettingUplineBonus;
use App\PromoItemTitle;
use App\SettingPackageRebate;
use App\FlashSale;
use App\FlashSaleProductDetail;
use App\FlashSaleProductPrice;
use App\AddOnDeal;
use App\AddOnDealItem;
use App\AddOnDealSubItem;
use App\Agent;
use App\CartLink;
use App\Http\Controllers\API\EinvoiceController;
use App\PackageItem;
use App\VariationTitle;
use App\SettingWebsiteMessage;
use App\SettingSecondBanner;
use App\Quiz;
use App\Blog;
use App\Faq;

use App\Http\Controllers\GlobalController;
use App\Http\Controllers\HomeController;
use App\QrCode;
use App\QrPayList;
use App\SettingEinvoice;
use Validator, Redirect, Toastr, DB, File, Auth, Mail, Arr;

class AjaxController extends Controller
{
  public function UploadMaterial(Request $request, $id)
  {
    $files = $request->file('file');
    $name = $files->getClientOriginalName();
    $exp = explode(".", $name);
    $file_ext = end($exp);
    $name = md5($name . date('Y-m-d H:i:s')) . '.' . $file_ext;
    $ori_name = $files->getClientOriginalName();

    $input = $request->all();
    if ($id == 0) {
      $input['type_id'] = $id;
      $input['status'] = '99';
      $input['images'] = "uploads/" . $name;

      $files->move(GlobalController::get_image_path("uploads/"), $name);
    } else {
      if ($id == 1) {
        if ($file_ext != 'jpeg' && $file_ext != 'jpg' && $file_ext != 'png') {
          return false;
        }
      }
      if ($id == 2) {
        if ($file_ext != 'mp4' && $file_ext != 'gif') {
          return false;
        }
      }
      if ($id == 3) {
        if ($file_ext != 'pdf') {
          return false;
        }
      }
      $input['type_id'] = $id;
      $input['status'] = '1';
      $input['images'] = "uploads/download_material/" . $id . "/" . $name;
      if ($id == 3) {
        $input['file_name'] = $ori_name;
      }

      $files->move(GlobalController::get_image_path("uploads/download_material/" . $id . "/"), $name);
    }

    $product_image = SettingDownloadMaterial::create($input);


    if ($id == 0) {
      $select = SettingDownloadMaterial::where('status', '99')->get();
    } else {
      $select = SettingDownloadMaterial::where('status', '1')
        ->where('type_id', $id)
        ->get();
    }

    $image_list = "";
    if (!$select->isEmpty()) {
      foreach ($select as $key => $value) {
        $exp = explode(".", $value->images);
        $file_ext = end($exp);

        $image_list .= '<div class="product-image-thumbnail">
                                    <div class="form-group">
                                        <div class="delete-image-box">
                                            <a href="#" class="delete-image" data-id="' . $value->id . '">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </div>';
        if ($file_ext == 'mp4') {
          $image_list .= '<video id="myVideo" style="width: 100%;" controls>
                                    <source src="' . GlobalController::get_production_url($value->images) . '" type="video/mp4">
                                  </video>';
        } elseif ($file_ext == 'pdf') {
          $image_list .= '<iframe src="' . GlobalController::get_production_url($value->images) . '" width="100%" style="height:100%"></iframe>';
        } else {
          $image_list .= '<div class="product-image-thumbnail-img" style="background-image: url(' . GlobalController::get_production_url($value->images) . ')"></div>';
        }
        $image_list .= '</div>
                                </div>';
      }
    }

    return array($image_list, $id);
  }

  public function LoadMaterialImage($id)
  {

    $select = SettingDownloadMaterial::where('status', '1')
      ->where('type_id', $id)
      ->get();


    $image_list = "";
    if (!$select->isEmpty()) {
      foreach ($select as $key => $value) {
        $exp = explode(".", $value->images);
        $file_ext = end($exp);
        $image_list .= '<div class="product-image-thumbnail">
                                    <div class="form-group">
                                        <div class="delete-image-box">
                                            <a href="#" class="delete-image" data-id="' . $value->id . '">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </div>';
        if ($file_ext == 'mp4') {
          $image_list .= '<video id="myVideo" style="width: 100%;" controls>
                                    <source src="' . GlobalController::get_production_url($value->images) . '" type="video/mp4">
                                  </video>';
        } elseif ($file_ext == 'pdf') {
          $image_list .= '<iframe src="' . GlobalController::get_production_url($value->images) . '" width="100%" style="height:100%"></iframe>';
        } else {
          $image_list .= '<div class="product-image-thumbnail-img" style="background-image: url(' . GlobalController::get_production_url($value->images) . ')"></div>';
        }
        $image_list .= '</div>
                </div>';
      }
    }

    return $image_list;
  }


  public function DeleteMaterialImage($id)
  {
    $delete = SettingDownloadMaterial::find($id);
    File::delete($delete->images);
    $delete = $delete->delete();
  }

  public function uploadImage(Request $request, $id)
  {

    $files = $request->file('file');
    $name = $files->getClientOriginalName();
    $exp = explode(".", $name);
    $file_ext = end($exp);
    $name = md5($name . date('Y-m-d H:i:s')) . '.' . $file_ext;

    $input = $request->all();
    if ($id == 0) {
      $input['product_id'] = $id;
      $input['status'] = '99';
      $input['image'] = "uploads/" . $name;

      $files->move(GlobalController::get_image_path("uploads/"), $name);
    } else {
      $input['product_id'] = $id;
      $input['status'] = '1';
      $input['image'] = "uploads/" . $id . "/" . $name;

      $files->move(GlobalController::get_image_path("uploads/" . $id . "/"), $name);
    }
    $product_image = ProductImage::create($input);


    if ($id == 0) {
      $select = ProductImage::where('status', '99')->get();
    } else {
      $select = ProductImage::where('status', '1')
        ->where('product_id', $id)
        ->get();
    }
    // return 123;

    $image_list = "";
    if (!$select->isEmpty()) {
      foreach ($select as $key => $value) {

        $exp = explode(".", $value->image);
        $file_ext = end($exp);

        $image_list .= '<div class="product-image-thumbnail" data-id="' . $value->id . '">
        									<div class="form-group" style="width: 100%;">
        										<div class="delete-image-box">
        											<a href="#" class="delete-image" data-id="' . $value->id . '">
        												<i class="bi bi-trash"></i>
        											</a>
        										</div>';
        if ($file_ext == 'mp4') {
          $image_list .= '<video id="myVideo" style="width: 100%;" >
                            <source src="' . GlobalController::get_production_url($value->image) . '" type="video/mp4">
                          </video>';
        } else {
          $image_list .= '<div class="product-image-thumbnail-img" style="background-image: url(' . GlobalController::get_production_url($value->image) . ')"></div>';
        }
        $image_list .= '</div>
        								</div>';
      }
    }

    // return json_encode($image_list);
    return $image_list;
  }

  public function LoadImage($id)
  {
    if ($id == 0) {
      $select = ProductImage::where('status', '99')->orderBy('sort_level', 'asc')->get();
    } else {
      $select = ProductImage::where('status', '1')
        ->where('product_id', $id)
        ->orderBy('sort_level', 'asc')
        ->get();
    }

    $image_list = "";
    if (!$select->isEmpty()) {
      foreach ($select as $key => $value) {
        $exp = explode(".", $value->image);
        $file_ext = end($exp);

        $image_list .= '<div class="product-image-thumbnail" data-id="' . $value->id . '">
                          <div class="form-group" style="width: 100%;">
                            <div class="delete-image-box">
                              <a href="#" class="delete-image" data-id="' . $value->id . '">
                                <i class="bi bi-trash"></i>
                              </a>
                            </div>';
        if ($file_ext == 'mp4') {
          $image_list .= '<video id="myVideo" style="width: 100%;" autoplay="autoplay" loop="1">
                            <source src="' . GlobalController::get_production_url($value->image) . '" type="video/mp4">
                          </video>';
        } else {
          $image_list .= '<div class="product-image-thumbnail-img" style="background-image: url(' . GlobalController::get_production_url($value->image) . ')"></div>';
        }
        $image_list .= '</div>
                        </div>';
      }
    }

    return $image_list;
  }

  public function DeleteImage($id)
  {
    $delete = ProductImage::find($id);
    File::delete($delete->image);
    $delete = $delete->delete();
  }

  public function SortImage(Request $request)
  {
    $images = ProductImage::find($request->mid);
    $images = $images->update(['sort_level' => $request->number]);
  }

  public function uploadBannerImage(Request $request)
  {
    $files = $request->file('file');
    $name = $files->getClientOriginalName();
    $exp = explode(".", $name);
    $file_ext = end($exp);
    $name = md5($name . date('Y-m-d H:i:s')) . '.' . $file_ext;

    $input = $request->all();

    $input['status'] = '1';
    $input['image'] = "uploads/banner/" . $name;

    if (Auth::guard('merchant')->check()) {
      $input['merchant_id'] = Auth::user()->code;
    }

    $files->move(GlobalController::get_image_path(("uploads/banner/")), $name);

    $product_image = SettingBanner::create($input);


    $select = SettingBanner::where('status', '1');
    if (Auth::guard('merchant')->check()) {
      $select = $select->where('merchant_id', Auth::user()->code);
    }
    $select = $select->get();


    $image_list = "";
    if (!$select->isEmpty()) {
      foreach ($select as $key => $value) {
        $image_list .= '<div class="product-image-thumbnail" data-id="' . $value->id . '">
                                    <div class="form-group">
                                        <div class="delete-image-box">
                                            <a href="#" class="delete-image" data-id="' . $value->id . '">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </div>
                                        <div class="product-image-thumbnail-img" style="background-image: url(' . GlobalController::get_production_url($value->image) . ')"></div>
                                    </div>
                                </div>';
      }
    }

    return $image_list;
  }

  public function LoadBannerImage()
  {
    $select = SettingBanner::where('status', '1')->orderBy('sort_level', 'asc');

    if (Auth::guard('merchant')->check()) {
      $select = $select->where('merchant_id', Auth::user()->code);
    }
    $select = $select->get();

    $image_list = "";
    if (!$select->isEmpty()) {
      foreach ($select as $key => $value) {
        $image_list .= '<div class="product-image-thumbnail" data-id="' . $value->id . '">
                                    <div class="form-group">
                                        <div class="delete-image-box">
                                            <a href="#" class="delete-image" data-id="' . $value->id . '">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </div>
                                        <div class="product-image-thumbnail-img" style="background-image: url(' . GlobalController::get_production_url($value->image) . ')"></div>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="banner_url" class="form-control banner_url" data-id="' . $value->id . '" placeholder="https://..." value="' . $value->url . '">
                                    </div>
                                </div>';
      }
    }

    return $image_list;
  }

  public function DeleteBannerImage($id)
  {
    $delete = SettingBanner::find($id);
    File::delete($delete->image);
    $delete = $delete->delete();
  }

  public function changeBannerUrl(Request $request)
  {
    $banner = SettingBanner::find($request->bid);
    $banner = $banner->update(['url' => $request->url]);
  }

  public function uploadSignatureDishImage(Request $request)
  {
    $files = $request->file('file');
    $name = $files->getClientOriginalName();
    $exp = explode(".", $name);
    $file_ext = end($exp);
    $name = md5($name . date('Y-m-d H:i:s')) . '.' . $file_ext;

    $input = $request->all();

    $input['status'] = '1';
    $input['image'] = "uploads/signature_dish/" . $name;

    $files->move(GlobalController::get_image_path("uploads/signature_dish/"), $name);

    $product_image = SettingSignatureDish::create($input);


    $select = SettingSignatureDish::where('status', '1')->get();


    $image_list = "";
    if (!$select->isEmpty()) {
      foreach ($select as $key => $value) {
        $image_list .= '<div class="product-image-thumbnail">
                            <div class="form-group">
                              <div class="delete-image-box">
                                <a href="#" class="delete-image" data-id="' . $value->id . '">
                                  <i class="bi bi-trash"></i>
                                </a>
                              </div>
                              <div class="product-image-thumbnail-img" style="background-image: url(' . GlobalController::get_production_url($value->image) . ')"></div>
                            </div>
                          </div>';
      }
    }

    return json_encode($image_list);
  }

  public function LoadSignatureDishImage()
  {
    $select = SettingSignatureDish::where('status', '1')->get();

    $image_list = "";
    if (!$select->isEmpty()) {
      foreach ($select as $key => $value) {
        $image_list .= '<div class="product-image-thumbnail">
                  <div class="form-group">
                    <div class="delete-image-box">
                      <a href="#" class="delete-image" data-id="' . $value->id . '">
                        <i class="bi bi-trash"></i>
                      </a>
                    </div>
                    <div class="product-image-thumbnail-img" style="background-image: url(' . GlobalController::get_production_url($value->image) . ')"></div>
                  </div>
                </div>';
      }
    }

    return $image_list;
  }

  public function DeleteSignatureDishImage($id)
  {
    $delete = SettingSignatureDish::find($id);
    File::delete($delete->image);
    $delete = $delete->delete();
  }

  public function uploadCategoryImage(Request $request, $id)
  {
    $image = $request->image;  // your base64 encoded
    $image = str_replace('data:image/png;base64,', '', $image);
    $image = str_replace(' ', '+', $image);
    $imageName = str_random(10) . '.' . 'png';

    $input = $request->all();
    if ($id == 0) {

      $input['status'] = '99';
      $input['image'] = "uploads/" . $imageName;

      \File::put('uploads/' . '/' . $imageName, base64_decode($image));

      $cat_image = CategoryImage::create($input);
    } else {

      $input['status'] = '1';
      $input['image'] = "uploads/" . $imageName;

      \File::put('uploads/' . $imageName, base64_decode($image));

      $category = CategoryImage::where('category_id', $id)->first();
      if (!empty($category)) {
        $category = CategoryImage::where('category_id', $id)->update($input);
      } else {
        $input['category_id'] = $id;
        $cat_image = CategoryImage::create($input);
      }
    }

    // 


    if ($id == 0) {
      $select = CategoryImage::where('status', '99')->get();
    } else {
      $select = CategoryImage::where('status', '1')
        ->where('category_id', $id)
        ->get();
    }

    $image_list = "";
    if (!$select->isEmpty()) {
      foreach ($select as $key => $value) {
        $image_list .= '<div class="product-image-thumbnail">
                              <div class="form-group">
                                <div class="delete-image-box">
                                  <a href="#" class="delete-image" data-id="' . $value->id . '">
                                    <i class="bi bi-trash"></i>
                                  </a>
                                </div>
                                <div class="product-image-thumbnail-img" style="background-image: url(' . GlobalController::get_production_url($value->image) . ')"></div>
                              </div>
                            </div>';
      }
    }
    return $image_list;
  }


  public function LoadCategoryImage($id)
  {
    if ($id == 0) {
      $select = CategoryImage::where('status', '99')->get();
    } else {
      $select = CategoryImage::where('status', '1')
        ->where('category_id', $id)
        ->get();
    }


    $image_list = "";
    if (!$select->isEmpty()) {
      foreach ($select as $key => $value) {
        $image_list .= '<div class="product-image-thumbnail">
                    <div class="form-group">
                      
                      <div class="product-image-thumbnail-img" style="background-image: url(' . GlobalController::get_production_url($value->image) . ')"></div>
                    </div>
                  </div>';
      }
    }
    return $image_list;
  }
  public function DeleteCategoryImage($id)
  {
    $delete = CategoryImage::find($id);
    File::delete($delete->image);
    $delete = $delete->delete();
  }

  public function change_withdrawal_transaction_action(Request $request)
  {
    $input = $request->all();
    $input['status'] = $request->action_id;

    $withdrawal = WithdrawalTransaction::find($request->tid);
    $withdrawal = $withdrawal->update($input);
  }

  public function ApproveAllWithdrawal(Request $request)
  {
    if (!empty($request->arrayA)) {

      $explode = explode(",", $request->arrayA);

      $withdrawal = WithdrawalTransaction::whereIn('id', $explode)->update(['status' => '1']);
    }
  }

  public function ApproveRejectMerchant(Request $request)
  {
    try {
      \DB::beginTransaction();

      $agent = Agent::find($request->mid);
      if ($request->action_id == '98') {
        $agent->status = 3;
        $agent->email = $agent->email . '_rejected';
        $agent->phone = $agent->phone . '_rejected';
        $agent->ic = $agent->ic . '_rejected';
        $agent->save();

        $transaction = TopupTransaction::whereUserId($agent->code)->first();
        if (!empty($transaction->id)) {
          $transaction->status = 95;
          $transaction->save();
        }

        $shipping_address = UserShippingAddress::where('user_id', $agent->code)->delete();
      } else {
        $agent->status = 1;
        $agent->verify_status = 1;
        $agent->lvl = 1;
        $agent->save();

        $add_affiliates = GlobalController::add_affiliates($agent->code, $agent->master_id);
        if ($add_affiliates != 'ok') {
          throw new \Exception($add_affiliates);
        }

        $referral_bonus = GlobalController::referral_bonus($agent->code, $agent->lvl);
        if ($referral_bonus != 'ok') {
          throw new \Exception($referral_bonus);
        }

        if (!empty($agent->register_transaction)) {
          $transaction = Transaction::where('transaction_no', $agent->register_transaction)->where('status', '98')->first();

          // throw new \Exception($agent->register_transaction);

          if (!empty($transaction->id)) {
            $transaction_voucher_assign = GlobalController::transaction_voucher_assign($transaction->id);
            if ($transaction_voucher_assign != 'ok') {
              throw new \Exception($transaction_voucher_assign);
            }

            $upgrade_agent_with_package = GlobalController::upgrade_agent_with_package($transaction->transaction_no);
            if ($upgrade_agent_with_package != 'ok') {
              throw new \Exception($upgrade_agent_with_package);
            }


            if (empty($transaction->commission_disabled)) {
              // $rebate_commission = GlobalController::rebate_commission($transaction->user_id, $transaction->transaction_no);
              // if ($rebate_commission != 'ok') {
              //   throw new \Exception($rebate_commission);
              // }

              $heirarchy_commission = GlobalController::heirarchy_commission($transaction->user_id, $transaction->transaction_no);
              if ($heirarchy_commission != 'ok') {
                throw new \Exception($heirarchy_commission);
              }
            }

            $transaction->status = 1;
            $transaction->save();
          }
        }
      }

      \DB::commit();
    } catch (\Exception $e) {
      \DB::rollback();
      return $e->getMessage();
    } catch (\Error $e) {
      return $e->getMessage();
    }

    return "ok";
  }

  public function VerifyMerchant(Request $request)
  {
    $input = $request->all();
    $input['partner_lvl'] = 1;
    $input['partner_lvl_verify'] = NULL;
    $user = Merchant::find($request->mid);
    $user = $user->update($input);
  }

  public function ApproveRejectMember(Request $request)
  {
    $input = $request->all();
    $input['status'] = $request->action_id;
    $user = User::find($request->mid);

    $member = User::find($request->mid);

    if ($request->action_id == '98') {
      $user = $user->update([
        'email' => $user->email . '_rejected',
        'phone' => $user->phone . '_rejected',
        'ic' => $user->ic . '_rejected',
        'status' => '3'
      ]);
    } else {
      if ($user->status != 1) {
        $Mupline = Merchant::where('code', $agent->master_id)->first();
        $Aupline = Admin::where('code', $agent->master_id)->first();
        $Uupline = User::where('code', $agent->master_id)->where('status', '1')->where('lvl', '1')->first();

        if (!empty($Mupline)) {
          $upline_name = $Mupline->f_name;
          $upline_email = $Mupline->email;
        } elseif (!empty($Uupline)) {
          $upline_name = $Uupline->f_name;
          $upline_email = $Uupline->email;
        } else {
          $upline_name = $Aupline->f_name;
          $upline_email = $Aupline->email;
        }

        if ($user->master_id == 'AD000001') {
          $affiliate = Affiliate::create([
            'affiliate_id' => $user->code,
            'user_id' => 'AD000001',
            'sort_level' => '1'
          ]);
        } else {
          //downline
          $create = Affiliate::create([
            'affiliate_id' => $user->code,
            'user_id' => $user->master_id,
            'sort_level' => '1'
          ]);

          $getAff = Affiliate::where('affiliate_id', $user->master_id)->orderBy('id', 'asc')->get();
          $affiliate = [];
          $sort_level = 2;
          foreach ($getAff as $aff) {

            $affiliate[] = [
              'affiliate_id' => $user->code,
              'user_id' => $aff->user_id,
              'sort_level' => $sort_level++,
              'status' => '1',
              'created_at' => date('Y-m-d H:i:s'),
              'updated_at' => date('Y-m-d H:i:s'),
            ];
          }
          $insert = Affiliate::insert($affiliate);
        }

        $user = $user->update(['status' => '1']);
        // $this->NewDownlineMessage($upline_email, 'noreply@kimcafe.com.my', $upline_name, 'New Downline', $member);
      }
    }
  }

  public function ApproveRejectCorporate(Request $request)
  {
    $input = $request->all();
    $input['status'] = $request->action_id;
    $user = Corporate::find($request->mid);

    $member = Corporate::find($request->mid);

    if ($request->action_id == '98') {
      $user = $user->update([
        'email' => $user->email . '_rejected',
        'phone' => $user->phone . '_rejected',
        'ic' => $user->ic . '_rejected'
      ]);
    } else {
      if ($user->status != 1) {
        $user = $user->update(['status' => '1']);
      }
    }
  }

  public function NewDownlineMessage($to, $from, $name, $subject, $user)
  {
    $headers = "From: $from";
    $headers = "From: " . $from . "\r\n";
    $headers .= "Reply-To: " . $from . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    // $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8";
    $headers .= '<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">';

    // $subject = "Testing.";


    $link = 'www.weshare.my';

    $body = "<!DOCTYPE html><html lang='en'><head><meta charset='UTF-8'><title></title></head><body>";
    $body .= "<table style='width: 100%;'>";
    $body .= "<thead style='text-align: center;'><tr><td style='border:none;' colspan='2'>";

    $body .= "</td></tr></thead><tbody><tr>";
    $body .= "<td style='border:none;'><strong>WELCOME TO KIM CAFE E-CORMMERCE!</strong></td></tr>";
    $body .= "<tr>
                    <td style='border:none;'>
                      <strong>Dear " . $name . "</strong>
                    </td>
                  </tr>";
    $body .= "<tr><td>Congratulations！There is a new member added to your team！</td></tr>
                  <tr><td>恭喜您的团队又增加了一位新成员!</td></tr>";
    $body .= "<tr><td>New member information below :</td></tr>";
    $body .= "<tr><td>USER ID: " . $user->email . "</td></tr>";
    $body .= "<tr><td>Dealer ID: " . $user->code . "</td></tr>";
    $body .= "<tr><td>Contact: " . $user->phone . "</td></tr>";
    $body .= "<tr><td>Email : " . $user->email . "</td></tr>";
    $body .= "<tr><td></td></tr>";
    $body .= "<tr><td></td></tr>";
    $body .= "<tr><td>Regards,</td></tr>";
    $body .= "<tr><td>KIM CAFE E-CORMMERCE</td></tr>";
    $body .= "<tr><td></td></tr>";
    // $body .= "<tr><td colspan='2' style='border:none;'>{$cmessage}</td></tr>";
    $body .= "</tbody></table>";
    $body .= "</body></html>";

    $send = mail($to, $subject, $body, $headers);
  }

  public function AgentUpgrade()
  {
    $merchants = Merchant::where('status', '1')->get();

    foreach ($merchants as $merchant) {
      if ($merchant->lvl == '1') {
        $downline = Merchant::where('master_id', $merchant->code)
          ->where('status', '1')
          ->where('lvl', '>=', '1')
          ->get();
        if (count($downline) >= 3) {
          $upgrade = Merchant::find($merchant->id)->update(['lvl' => '2']);
        }
      }

      if ($merchant->lvl == '2') {
        $downline = Merchant::where('master_id', $merchant->code)
          ->where('status', '1')
          ->where('lvl', '>=', '2')
          ->get();
        if (count($downline) >= 3) {
          $upgrade = Merchant::find($merchant->id)->update(['lvl' => '3']);
        }
      }
    }
  }

  public function getTotalGroupTopup($user_id)
  {

    $merchant = Merchant::where('code', $user_id)->first();

    $downlineAffs = Affiliate::select('affiliates.sort_level', 'affiliates.affiliate_id', 'u.f_name')
      ->join('merchants AS u', 'u.code', 'affiliates.affiliate_id')
      ->where('user_id', $user_id)
      ->orderBy('sort_level', 'asc')
      ->get();

    $myGroup = [];
    foreach ($downlineAffs as $aff) {
      $myGroup[] = $aff->affiliate_id;
    }

    $myGroupTopup = TopupTransaction::select(DB::raw('SUM(actual_amount) as totalAmount'))
      ->where('status', '1')
      ->whereIn('user_id', $myGroup)
      ->first();

    $myTopup = TopupTransaction::select(DB::raw('SUM(actual_amount) as totalAmount'))
      ->where('status', '1')
      ->where('user_id', $user_id)
      ->first();


    $myGroupTotal = $myTopup->totalAmount + $myGroupTopup->totalAmount;

    $levels = AgentLevel::where('buy_quantity', '<=', $myGroupTotal)
      ->orderBy('id', 'desc')
      ->first();

    if (!empty($levels->id) && $merchant->lvl < $levels->id) {
      $ownLevel = Merchant::where('code', $user_id);
      $ownLevel = $ownLevel->update(['lvl' => $levels->id]);
    }

    return $myGroupTotal;
  }

  public function SetPermission(Request $request)
  {
    $select = Permission::where('permission_lvl', $request->permission_lvl)
      ->where('page', $request->page)
      ->first();

    if (!empty($select->id)) {
      $update = Permission::find($select->id);
      $update = $update->delete();
    } else {
      $input = $request->all();
      $create = Permission::create($input);
    }
  }

  public function UnsetPermission(Request $request)
  {
    $select = Permission::where('permission_lvl', $request->permission_lvl)
      ->where('page', $request->page)
      ->first();

    if (!empty($select->id)) {
      $update = Permission::where('permission_lvl', $request->permission_lvl)
        ->where('page', $request->page)->delete();
    } else {
      $input = $request->all();
      $create = Permission::create($input);
    }
  }

  public function GetPermission()
  {
    $selects = Permission::get();
    return $selects;
  }

  public function getItemCode(Request $request)
  {
    $code = GlobalController::product_item_code($request->cid, $request->pid);

    return $code;
  }

  public function getSubItemCode(Request $request)
  {

    $category = Category::find($request->cid);
    $sub_category = SubCategory::find($request->scid);
    if (empty($category->id)) {
      return "null";
    }
    $pCount = Product::select(DB::raw('COUNT(id) AS TotalCount'))
      ->where('category_id', $request->cid)
      ->first();

    $product = Product::where('id', $request->pid)
      ->where('category_id', $request->cid)
      ->first();

    if (!empty($product->code)) {
      return $category->code . $product->code;
    } else {
      $totalCount = $pCount->TotalCount + 1;
    }

    if (strlen($totalCount) == 1) {
      $code = $category->code . '-' . $sub_category->sub_category_code . "00" . $totalCount;
    } elseif (strlen($totalCount) == 2) {
      $code = $category->code . '-' . $sub_category->sub_category_code . "0" . $totalCount;
    } else {
      $code = $category->code . '-' . $sub_category->sub_category_code . $totalCount;
    }

    return $code;
  }

  public function MerchantStatus(Request $request)
  {
    try {
      \DB::beginTransaction();

      $table = Merchant::find($request->row_id);
      $table->status = $request->status;
      if ($request->status == 3) {
        $table->email = $table->email . '_deleted';
        $table->phone = $table->phone . '_deleted';
        $table->ic = $table->ic . '_deleted';
      }
      $table->save();

      \DB::commit();
    } catch (\Exception $e) {
      \DB::rollback();
      return Redirect::back()->withInput($request->all())->withErrors($e->getMessage());
    } catch (\Error $e) {
      \DB::rollback();
      return Redirect::back()->withInput($request->all())->withErrors($e->getMessage());
    }
  }

  public function AgentStatus(Request $request)
  {
    try {
      \DB::beginTransaction();

      $table = Agent::find($request->row_id);
      $table->status = $request->status;
      if ($request->status == 3) {
        $table->email = $table->email . '_deleted';
        $table->phone = $table->phone . '_deleted';
        $table->ic = $table->ic . '_deleted';
      }
      $table->save();

      \DB::commit();
      echo "ok";
    } catch (\Exception $e) {
      \DB::rollback();
      return Redirect::back()->withInput($request->all())->withErrors($e->getMessage());
    } catch (\Error $e) {
      \DB::rollback();
      return Redirect::back()->withInput($request->all())->withErrors($e->getMessage());
    }
  }

  public function UserStatus(Request $request)
  {
    // $input = $request->all();
    // $input['status'] = $request->status;
    // $table = User::find($request->row_id);
    // $table = $table->update($input);
    try {
      \DB::beginTransaction();

      $table = User::find($request->row_id);
      $table->status = $request->status;
      if ($request->status == 3) {
        $table->email = $table->email . '_deleted';
        $table->phone = $table->phone . '_deleted';
        $table->ic = $table->ic . '_deleted';
      }
      $table->save();

      \DB::commit();
    } catch (\Exception $e) {
      \DB::rollback();
      return Redirect::back()->withInput($request->all())->withErrors($e->getMessage());
    } catch (\Error $e) {
      \DB::rollback();
      return Redirect::back()->withInput($request->all())->withErrors($e->getMessage());
    }
  }

  public function PromotionItemStatus(Request $request)
  {
    $input = $request->all();
    $input['status'] = $request->status;
    $table = PromoItemTitle::find($request->row_id);
    $table = $table->update($input);


    $inputItem = $request->all();
    $inputItem['status'] = $request->status;
    $update_item = PromoAgentItem::where('title_id', $request->row_id)
      ->get();
    foreach ($update_item as $item) {
      $updated_item = $item->update($inputItem);
    }


    $each_item = PromoAgentItem::where('title_id', $request->row_id)
      ->get();
    foreach ($each_item as $item) {
      $inputItemStatus = $request->all();
      $inputItemStatus['status'] = $request->status;
      $each_item_detail = PromoAgentItemDetail::where('promo_item_id', $item->id)
        ->get();
      foreach ($each_item_detail as $item_detail) {
        $updated_item_detail = $item_detail->update($inputItemStatus);
      }
    }
  }

  public function CartLinkStatus(Request $request)
  {
    try {
      \DB::beginTransaction();

      $table = CartLink::find($request->row_id);
      $table->status = $request->status;
      $table->save();

      \DB::commit();
    } catch (\Exception $e) {
      \DB::rollback();
      return Redirect::back()->withInput($request->all())->withErrors($e->getMessage());
    } catch (\Error $e) {
      \DB::rollback();
      return Redirect::back()->withInput($request->all())->withErrors($e->getMessage());
    }
  }

  public function CorporateStatus(Request $request)
  {
    $input = $request->all();
    $input['status'] = $request->status;
    $table = Corporate::find($request->row_id);
    $table = $table->update($input);
  }

  public function StaffStatus(Request $request)
  {
    $input = $request->all();
    $input['status'] = $request->status;
    $table = Staff::find($request->row_id);
    $table = $table->update($input);
  }

  public function ProductStatus(Request $request)
  {
    $input = $request->all();
    $input['status'] = $request->status;
    $table = Product::find($request->row_id);
    $table = $table->update($input);
  }

  public function BundleStatus(Request $request)
  {
    $input = $request->all();
    $input['status'] = $request->status;
    $table = Bundle::find($request->row_id);
    $table = $table->update($input);
  }

  public function CategoryStatus(Request $request)
  {
    $input = $request->all();
    $input['status'] = $request->status;
    $table = Category::find($request->row_id);
    $table = $table->update($input);
  }

  public function FeedbackStatus(Request $request)
  {
    $input = $request->all();
    $input['status'] = $request->status;
    $table = SettingFeedback::find($request->row_id);
    $table = $table->update($input);
  }

  public function SubCategoryStatus(Request $request)
  {
    $input = $request->all();
    $input['status'] = $request->status;
    $table = SubCategory::find($request->row_id);
    $table = $table->update($input);
  }

  public function BrandStatus(Request $request)
  {
    $input = $request->all();
    $input['status'] = $request->status;
    $table = Brand::find($request->row_id);
    $table = $table->update($input);
  }

  public function PromotionStatus(Request $request)
  {
    $input = $request->all();
    $input['status'] = $request->status;
    $table = Promotion::find($request->row_id);
    $table = $table->update($input);
  }

  public function BankStatus(Request $request)
  {
    $input = $request->all();
    $input['status'] = $request->status;
    $table = BankAccount::find($request->row_id);
    $table = $table->update($input);
  }

  public function PaymentBankStatus(Request $request)
  {
    $input = $request->all();
    $input['status'] = $request->status;
    $table = PaymentBank::find($request->row_id);
    $table = $table->update($input);
  }

  public function FlashSaleStatus(Request $request)
  {
    // $input = $request->all();
    // $input['status'] = $request->status;
    // $table = FlashSale::find($request->row_id);
    // if ($request->status == '1') {
    //   $inactive_other_flash_sale = FlashSale::whereNotIn('id', [$request->row_id])
    //     ->where('status', '1')
    //     ->update(['status' => '2']);
    // }
    // $table = $table->update($input);

      $translation_data = GlobalController::get_translations();
      try {
          \DB::beginTransaction();

          $flash_sale = FlashSale::find($request->row_id);
          $flash_sale->status = $request->status;

          if ($request->status == '1') {
              $inactive_other_flash_sales = FlashSale::whereNotIn('id', [$request->row_id])
                                                     ->where('status', '1')
                                                     ->get();

              foreach ($inactive_other_flash_sales as $inactive_other_flash_sale) {
                  $inactive_other_flash_sale->status = 2;
                  $inactive_other_flash_sale->save();
              }

              if (date('Y-m-d H:i:s') > $flash_sale->end) {
                  throw new \Exception($translation_data['backendlang']['backendlang']['Flash_Sale_Has_Ended_On'] ?? 'Flash Sale Has Ended On '.$flash_sale->end.'. '.$translation_data['backendlang']['backendlang']['Please_Adjust_The_End_Date_To_Activate'] ?? 'Please Adjust The End Date To Activate.');
              }
          }

          $flash_sale->save();

          \DB::commit();

          return "ok";
      } catch (\Exception $e) {
          \DB::rollback();
          return $e->getMessage().' - '.$e->getLine();
      } catch (\Error $e) {
          \DB::rollback();
          return $e->getMessage().' - '.$e->getLine();
      }
  }


  public function change_transaction_action(Request $request)
  {

    try {
      \DB::beginTransaction();
      $transaction = Transaction::find($request->tid);

      $amount = $transaction->grand_total - $transaction->shipping_fee - $transaction->processing_fee + $transaction->discount;

      if (empty($transaction->id)) {
        throw new \Exception('Transaction ID Error');
      }

      if ($request->action_id == '12') {
        $transaction->to_receive = 1;
      }

      if ($request->action_id == '11') {
        $transaction->completed = 1;
      }

      if ($request->action_id == '1') {
        if ($transaction->status != '1') {
          $isMember = User::where('code', $transaction->user_id)->first();
          $isAgent = Agent::where('code', $transaction->user_id)->first();
          $get_merchant_register = Agent::where('register_transaction', $transaction->transaction_no)->first();

          if (!empty($get_merchant_register->id)) {
            $get_merchant_register->status = 1;
            $get_merchant_register->lvl = 1;
            $get_merchant_register->verify_status = 1;
            $get_merchant_register->save();

            $add_affiliates = GlobalController::add_affiliates($get_merchant_register->code, $get_merchant_register->master_id);
            if ($add_affiliates != 'ok') {
              throw new \Exception($add_affiliates);
              // $Generate_Refferal_Reward = $this->Generate_Refferal_Reward($get_merchant->master_id);
            }

            $referral_bonus = GlobalController::referral_bonus($get_merchant_register->code, $get_merchant_register->lvl);
            if ($referral_bonus != 'ok') {
              throw new \Exception($referral_bonus);
            }
          }

          $transaction_voucher_assign = GlobalController::transaction_voucher_assign($transaction->id);
          if ($transaction_voucher_assign != 'ok') {
            throw new \Exception($transaction_voucher_assign);
          }

          if (
            !empty($isMember->id) ||
            !empty($isAgent->id)
          ) {
            $upgrade_agent_with_package = GlobalController::upgrade_agent_with_package($transaction->transaction_no);
            if ($upgrade_agent_with_package != 'ok') {
              throw new \Exception($upgrade_agent_with_package);
            }
          }
          if (empty($transaction->commission_disabled)) {

            if (empty($get_merchant_register->id)) {
              $rebate_commission = GlobalController::rebate_commission($transaction->user_id, $transaction->transaction_no);
              if ($rebate_commission != 'ok') {
                throw new \Exception($rebate_commission);
              }
            }

            $heirarchy_commission = GlobalController::heirarchy_commission($transaction->user_id, $transaction->transaction_no);
            if ($heirarchy_commission != 'ok') {
              throw new \Exception($heirarchy_commission);
            }
          }

          $purchase_from_customer_deduct_stock_commission = GlobalController::purchase_from_customer_deduct_stock_commission($transaction->transaction_no);
          if ($purchase_from_customer_deduct_stock_commission != 'ok') {
            throw new \Exception($purchase_from_customer_deduct_stock_commission);
          }

          $transaction->status = 1;
        }
      }

      if ($request->action_id == '95') {
        $cancel_commission = AffiliateCommission::where('transaction_no', $transaction->transaction_no)->update(['status' => '2']);
        // $commission = AffiliateCommission::where('transaction_no', $transaction->transaction_no)->first();
        // if(!empty($commission->id)){
        //     $commission->status = 2;
        //     $commission->save();
        // }

        $transaction->cancelled_by = Auth::user()->code;
        $transaction->status = 95;
      }

      if ($request->action_id == '96') {
        $transaction->cancelled_by = Auth::user()->code;
        $transaction->status = 96;
      }

      $transaction->save();

      \DB::commit();
    } catch (\Exception $e) {
      \DB::rollback();
      return $e->getMessage() . ' - ' . $e->getLine();
    } catch (\Error $e) {
      \DB::rollback();
      return $e->getMessage() . ' - ' . $e->getLine();
    }

    return "ok";
  }

  public function reprint_invoice(Request $request)
  {
    $transaction = Transaction::find($request->tid);

    if (empty($transaction->id)) {
      return 'Transaction not found';
    }

    try {
      (new OrderPrintService())->printOrder($transaction);
    } catch (\Exception $e) {
      return $e->getMessage();
    }

    return "ok";
  }



  public function UplineLvlUpgrade()
  {
    $merchants = Merchant::where('status', '1')->get();
    $website_setting = WebsiteSetting::find(1);

    foreach ($merchants as $merchant) {
      if ($merchant->lvl == 1) {
        $high_founding_member_required_period = 0;
        if (!empty($website_setting->high_founding_member_month_period)) {
          $high_founding_member_required_period = $website_setting->high_founding_member_month_period;
        }

        $high_add_month_period = date('Y-m-d', strtotime($merchant->upgrade_founding_member_date . '+6 month'));

        if (
          $high_founding_member_required_period == 0 ||
          ($high_founding_member_required_period > 0 && date('Y-m-d') <= $high_add_month_period)
        ) {
          $get_own_refferal = Merchant::where('master_id', $merchant->code)
            ->where('status', '1')
            ->where('lvl', '>=', '1')
            ->get();

          $founding_member_required_refferal_quantity = 0;

          if (!empty($website_setting->high_fouding_member_order_amount)) {
            $founding_member_required_amount = $website_setting->high_fouding_member_order_amount;
          }

          if (count($get_own_refferal) >= $founding_member_required_amount) {
            $upgrade_file_member = Merchant::where('code', $merchant->code)->update([
              'lvl' => '2',
              'upgrade_high_founding_member_date' => date('Y-m-d H:i:s')
            ]);
          }
        }
      }

      if (empty($merchant->partner_lvl)) {
        $get_own_downlines = Merchant::select(DB::raw('COUNT(id) as totalRefferal'))
          ->where('status', '1')
          ->where('master_id', $merchant->code)
          ->first();

        $get_partner_lvl_one = PartnerLevel::find(1);

        $getGroupSales = $this->getGroupSales($merchant->code);

        if ((!empty($get_partner_lvl_one->requirement) &&
            $get_own_downlines->totalRefferal >= $get_partner_lvl_one->requirement) &&
          (!empty($get_partner_lvl_one->promotion_requirement) &&
            $getGroupSales >= $get_partner_lvl_one->promotion_requirement)
        ) {

          $update_partner_lvl_up_verify = Merchant::where('code', $merchant->code)->update(['partner_lvl_verify' => $get_partner_lvl_one->id]);
        }
      }

      if ($merchant->partner_lvl >= '1') {
        $getGroupSales = $this->getGroupSales($merchant->code);

        $get_group_downlines = Affiliate::select(DB::raw('COUNT(affiliates.id) as totalRefferal'))
          ->join('merchants as m', 'm.code', 'affiliates.affiliate_id')
          ->where('user_id', $merchant->code)
          ->where('sort_level', '<=', '3')
          ->where('m.status', '1')
          ->orderBy('sort_level', 'asc')
          ->first();



        $get_partner_lvl = PartnerLevel::where('requirement', '<=', $get_group_downlines->totalRefferal)
          ->where('promotion_requirement', '<=', $getGroupSales)
          ->orderBy('requirement', 'desc')
          ->first();

        if (!empty($get_partner_lvl->id) && $merchant->lvl < $get_partner_lvl->id) {
          $update_partner_lvl_up_verify = Merchant::where('code', $merchant->code)->update(['partner_lvl' => $get_partner_lvl->id]);
        }
      }
    }
  }

  public function getGroupSales($user_id)
  {
    $affs = Affiliate::select(
      DB::raw('coalesce(m.lvl, a.lvl) as upline_lvl'),
      'affiliates.affiliate_id'
    )
      ->leftJoin('merchants as m', 'm.code', 'affiliates.affiliate_id')
      ->leftJoin('admins as a', 'a.code', 'affiliates.affiliate_id')
      ->where('user_id', $user_id)
      ->where('sort_level', '<=', '3')
      ->orderBy('sort_level', 'asc')
      ->get();

    $transaction = Transaction::select(DB::raw('SUM(grand_total) as totalSales'))
      ->where('user_id', $user_id)
      ->where('status', '1')
      ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), date('Y-m'))
      ->whereNull('mall')
      ->whereNull('pv_purchase')
      ->first();

    $team_sales = 0;

    foreach ($affs as $aff) {
      $trans = Transaction::select(DB::raw('SUM(grand_total) as totalSales'))
        ->where('user_id', $aff->affiliate_id)
        ->where('status', '1')
        ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), date('Y-m'))
        ->whereNull('mall')
        ->whereNull('pv_purchase')
        ->first();

      $team_sales += $trans->totalSales;
    }

    return $transaction->totalSales + $team_sales;
  }

  public function updateReceiveNewsletter(Request $request)
  {
    $currentUpdate = "";
    if ($request->isAgent == 1) {
      $currentUpdate = Merchant::find($request->id);
    } elseif ($request->isAgent == 0) {
      $currentUpdate = User::find($request->id);
    }

    if ($request->receive_newsletter == 1) {
      $currentUpdate = $currentUpdate->update(['receive_newsletter' => '1']);
    } else {
      $currentUpdate = $currentUpdate->update(['receive_newsletter' => '0']);
    }
  }

  protected function MerchantCode()
  {
    $user = Merchant::select(DB::raw("COUNT(id) AS totalUser"))->first();
    $totalCount = $user->totalUser + 1;

    if (strlen($totalCount) == '1') {
      $member_id = "M00000" . $totalCount;
    } elseif (strlen($totalCount) == '2') {
      $member_id = "M0000" . $totalCount;
    } elseif (strlen($totalCount) == '3') {
      $member_id = "M000" . $totalCount;
    } elseif (strlen($totalCount) == '4') {
      $member_id = "M00" . $totalCount;
    } elseif (strlen($totalCount) == '5') {
      $member_id = "M0" . $totalCount;
    } else {
      $member_id = "M" . $totalCount;
    }

    return $member_id;
  }

  public function GetGenerationCommision($level, $agent_lvl)
  {
    $comm = SettingMerchantCommission::where('level', $level)
      ->where('agent_lvl', $agent_lvl)
      ->first();
    if (!empty($comm->comm_amount)) {
      return array($comm->comm_type, $comm->comm_amount);
    } else {
      return array(0, 0);
    }
  }

  public function setFeatured(Request $request)
  {
    $product = Product::find($request->id);

    if ($product->featured == '1') {
      $product = $product->update(['featured' => '0']);
    } else {
      $product = $product->update(['featured' => '1']);
    }
  }

  public function setBirthdayPromotion(Request $request)
  {
    $product = Product::find($request->id);

    if ($product->birthday_promotion == '1') {
      $product = $product->update(['birthday_promotion' => '0']);
    } else {
      $product = $product->update(['birthday_promotion' => '1']);
    }
  }

  public function CKEditorUploadImage(Request $request)
  {
    if ($request->hasFile('upload')) {
      $originName = $request->file('upload')->getClientOriginalName();
      $fileName = pathinfo($originName, PATHINFO_FILENAME);
      $extension = $request->file('upload')->getClientOriginalExtension();
      $fileName = $fileName . '_' . time() . '.' . $extension;
      $path = "";
      if ($request->type == 1) {
        $path = "Description";
      } elseif ($request->type == 2) {
        $path = "Hiring";
      } elseif ($request->type == 3) {
        $path = "Mission";
      }

      $request->file('upload')->move(GlobalController::get_image_path('uploads/Product_description/'), $fileName);

      $CKEditorFuncNum = $request->input('CKEditorFuncNum');
      $url = asset('uploads/Product_description/' . $fileName);
      $msg = 'Image uploaded successfully';
      $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";

      @header('Content-type: text/html; charset=utf-8');
      echo $response;
    }
  }

  public function getProducts(Request $request)
  {
    $product = Product::find($request->product_id);

    if (!empty($product->id)) {
      return $product;
    } else {
      return 0;
    }
  }

  public function VariationBalanceQuantity($id)
  {
    $quantityAmount = ProductVariation::find($id);

    $cart = Cart::select(DB::raw('SUM(qty) AS InCart'))
      ->where('status', '1')
      ->where('sub_category_id', $id)
      ->first();

    $transaction = TransactionDetail::select(DB::raw('SUM(quantity) AS TransCart'))
      ->join('transactions AS t', 't.id', 'transaction_details.transaction_id')
      ->whereIn('t.status', ['1', '97', '98', '99'])
      ->where('variation_id', $id)
      ->first();

    return $quantityAmount->variation_stock - $cart->InCart - $transaction->TransCart;
  }

  public function SecondVariationBalanceQuantity($id)
  {
    $quantityAmount = ProductSecondVariation::find($id);

    $cart = Cart::select(DB::raw('SUM(qty) AS InCart'))
      ->where('status', '1')
      ->where('second_sub_category_id', $id)
      ->first();

    $transaction = TransactionDetail::select(DB::raw('SUM(quantity) AS TransCart'))
      ->join('transactions AS t', 't.id', 'transaction_details.transaction_id')
      ->whereIn('t.status', ['1', '97', '98', '99'])
      ->where('second_variation_id', $id)
      ->first();

    return $quantityAmount->variation_stock - $cart->InCart - $transaction->TransCart;
  }

  public function getOption(Request $request)
  {
    $variations = ProductVariation::where('product_id', $request->product_id)->get();

    $result = '<select class="form-control variation_option" name="variation_option' . $request->num . '" data-filter="' . $request->num . '">
                      <option value="">Select Option</option>';
    foreach ($variations as $variation) {
      $result .= '<option value="' . $variation->id . '">' . $variation->variation_name . '</option>';
    }
    $result .= '</select>';


    return $result;
  }

  public function getOptionPricing(Request $request)
  {
    $product = Product::find($request->product_id);
    $variations = ProductVariation::where('product_id', $request->product_id)->get();
    $inner_variations = ProductSecondVariation::where('product_id', $request->product_id)->get();
    $agent_levels = AgentLevel::get();
    $secnd_variations = ProductSecondVariation::select('product_second_variations.*', 'v.variation_name as v_variation_name')
      ->leftJoin('product_variations as v', 'v.id', 'product_second_variations.variation_id')
      ->where('product_second_variations.product_id', $request->product_id)
      ->get();

    $s_secnd_variations = [];
    foreach ($variations as $variation) {
      $s_secnd_variations[$variation->id] = ProductSecondVariation::where('variation_id', $variation->id)->get();
    }
    $vStock = [];
    foreach ($variations as $variation) {
      $vStock[$variation->id] = $this->VariationBalanceQuantity($variation->id);
    }

    $vStock2 = [];
    foreach ($secnd_variations as $variation) {
      $vStock2[$variation->id] = $this->SecondVariationBalanceQuantity($variation->id);
    }

    $settingAgentPrice = AgentPrice::where('status', '1')
      ->where('product_id', $product->id)
      ->get();

    $agent_pricings = AgentPrice::where('product_id', $product->id)->get();

    $agent_prices = [];
    $agent_special_prices = [];
    $agent_v_prices = [];
    $agent_v_special_prices = [];
    $agent_v2_prices = [];
    $agent_v2_special_prices = [];

    $agent_prices_ids = [];
    $agent_prices_v_ids = [];
    $agent_prices_v2_ids = [];
    foreach ($agent_pricings as $agent_pricing) {
      if ($product->second_variation_enable == 1 && $product->variation_enable == 1) {
        $agent_v2_prices[$agent_pricing->agent_lvl_id][$agent_pricing->variation_id][$agent_pricing->second_variation_id] = $agent_pricing->price;
        $agent_v2_special_prices[$agent_pricing->agent_lvl_id][$agent_pricing->variation_id][$agent_pricing->second_variation_id] = $agent_pricing->special_price;
        $agent_prices_v2_ids[$agent_pricing->agent_lvl_id][$agent_pricing->variation_id][$agent_pricing->second_variation_id] = $agent_pricing->id;
      } elseif ($product->variation_enable == 1 && empty($product->second_variation_enable)) {
        $agent_v_prices[$agent_pricing->agent_lvl_id][$agent_pricing->variation_id] = $agent_pricing->price;
        $agent_v_special_prices[$agent_pricing->agent_lvl_id][$agent_pricing->variation_id] = $agent_pricing->special_price;
        $agent_prices_v_ids[$agent_pricing->agent_lvl_id][$agent_pricing->variation_id] = $agent_pricing->id;
      } else {
        $agent_prices[$agent_pricing->agent_lvl_id] = $agent_pricing->price;
        $agent_special_prices[$agent_pricing->agent_lvl_id] = $agent_pricing->special_price;
        $agent_prices_ids[$agent_pricing->agent_lvl_id] = $agent_pricing->id;
      }
    }

    $result = '<div class="col-10">
                  <div class="form-group pricing-list">
                    <input type="hidden" class="form-control" name="variation_enable[]" value="1">
                    <table class="table table-bordered variation-list-child-row">
                      <tr>
                        <td class="variation_title">';
    if (isset($product)) {
      $result .=    $product->variation_title;
    } else {
      $result .=    'Name';
    }
    $result .=   '</td>';
    if (isset($product) && $product->variation_enable == 1 && $product->second_variation_enable == 1) {
      $result .=    '<td class="variation_two variation_two_title">';
      $product->second_variation_title;
      '</td>';
    } else {
      $result .=    '';
    }
    $result .=  '<td>Customer Price</td>
                        <td>Customer Special Price</td>';
    foreach ($agent_levels as $key => $agentlvl) {
      $result .=  '<td>' . $agentlvl->agent_lvl . '</td>
                        <td>' . $agentlvl->agent_lvl . '(Special Price)</td>';
    }
    // $result .=     '<td>Weight</td>
    //                 <td>Stock</td>';
    $result .=        '</tr>';

    if (isset($variations) && !$variations->isEmpty()) {
      if ($product->second_variation_enable == 1) {

        $lrow = 0;

        foreach ($variations as $vkey => $varia) {
          $var = count($s_secnd_variations[$varia->id]) + 1;
          $result .= '<tr data-id="0">
                                <td class="variation_option_display_' . $vkey . ' first_variation" data-id="0" rowspan="' . $var . '">';
          $result .= $varia->variation_name;
          $result .= '<input type="hidden" name="fvid_' . $request->num . '[]" value="' . $varia->id . '">';
          $result .= '  </td>
                              </tr>';

          $s1row = 0;
          foreach ($s_secnd_variations[$varia->id] as $s_secnd_variation) {
            $result .= '<tr class="added-v2-option_' . $s1row . ' added" data-id="' . $s1row . '">';
            $result .=    '<td class="variation_option_two_display_' . $s1row . ' variation_two">';
            $result .=        '<span>' . $s_secnd_variation->variation_name . '</span>';
            $result .=        '<input type="hidden" class="variation_option_two_value_' . $s1row . '" name="variation_option_two_value_' . $request->num . '_' . $vkey . '[]" value="' . $s_secnd_variation->variation_name . '">';
            $result .=        '<input type="hidden" name="rid_' . $request->num . '_' . $vkey . '[]" value="' . $s_secnd_variation->id . '">';
            $result .=    '</td>
                                    <td><input type="text" name="customer_price_' . $request->num . '_' . $vkey . '[]" class="form-control" value="' . $s_secnd_variation->variation_price . '"></td>';
            $result .=    '<td><input type="text" name="customer_special_price_' . $request->num . '_' . $vkey . '[]" class="form-control" value="' . $s_secnd_variation->variation_special_price . '"></td>';

            foreach ($agent_levels as $key => $agentlvl) {

              $in_price = (isset($agent_v2_prices[$agentlvl->id][$s_secnd_variation->variation_id][$s_secnd_variation->id])) ? $agent_v2_prices[$agentlvl->id][$s_secnd_variation->variation_id][$s_secnd_variation->id] : '';

              $in_special_price = (isset($agent_v2_special_prices[$agentlvl->id][$s_secnd_variation->variation_id][$s_secnd_variation->id])) ? $agent_v2_special_prices[$agentlvl->id][$s_secnd_variation->variation_id][$s_secnd_variation->id] : '';

              $in_ids = (isset($agent_prices_v2_ids[$agentlvl->id][$s_secnd_variation->variation_id][$s_secnd_variation->id])) ? $agent_prices_v2_ids[$agentlvl->id][$s_secnd_variation->variation_id][$s_secnd_variation->id] : '';

              $result .=    '<td>
                                            <input type="text" name="agent_level_price_' . $request->num . '_' . $vkey . '_' . $s1row . '[]" class="form-control" value="' . $in_price . '">';
              $result .=        '<input type="hidden" name="variation_agent_level_' . $request->num . '_' . $vkey . '_' . $s1row . '[]" value="' . $agentlvl->id . '" class="form-control">';
              $result .=        '<input type="hidden" name="variation_agent_level_id_' . $vkey . '_' . $s1row . '[]" value="' . $in_ids . '" class="form-control">';
              $result .=    '</td>
                                        <td>
                                            <input type="text" name="agent_level_special_price_' . $request->num . '_' . $vkey . '_' . $s1row . '[]" class="form-control" value="' . $in_special_price . '">';
              $result .=    '</td>';
            }

            // $result .=    '<td><input type="text" name="weight_'. $vkey .'[]" class="form-control" value="'. $s_secnd_variation->variation_weight .'"></td>';
            // $result .=    '<td><input type="text" name="stock_'. $vkey .'[]" class="form-control" value="'. $vStock2[$s_secnd_variation->id] .'"></td>';
            $result .= '</tr>';

            $s1row++;
          }

          $lrow++;
        }
      } else {

        $lrow = 0;

        foreach ($variations as $varia) {
          $result .=  '<tr data-id=' . $lrow . '>';
          $result .=      '<td class="variation_option_display_' . $lrow . ' first_variation" data-id="' . $lrow . '">';
          $result .=          $varia->variation_name;
          $result .= '<input type="hidden" name="fvid_' . $request->num . '[]" value="' . $varia->id . '">';
          $result .=          '<input type="hidden" name="rid_' . $request->num . '[]" value="">';
          $result .=      '</td>
                                  <td class="variation_option_two_display_0 variation_two" style="display: none;">';
          $result .=          '<span>Option</span>
                                      <input type="hidden" class="variation_option_two_value_0" name="variation_option_two_value_' . $request->num . '_0[]">';
          $result .=      '</td>
                                  <td><input type="text" name="customer_price_' . $request->num . '_' . $lrow . '[]" class="form-control" value="' . $varia->variation_price . '"></td>';
          $result .=      '<td><input type="text" name="customer_special_price_' . $request->num . '_' . $lrow . '[]" class="form-control" value="' . $varia->variation_special_price . '"></td>';
          foreach ($agent_levels as $key => $agentlvl) {
            $in_price = (isset($agent_v_prices[$agentlvl->id][$varia->id])) ? $agent_v_prices[$agentlvl->id][$varia->id] : '';

            $in_special_price = (isset($agent_v_special_prices[$agentlvl->id][$varia->id])) ? $agent_v_special_prices[$agentlvl->id][$varia->id] : '';

            $in_ids = (isset($agent_prices_v_ids[$agentlvl->id][$varia->id])) ? $agent_prices_v_ids[$agentlvl->id][$varia->id] : '';

            $result .=  '<td>
                                      <input type="text" name="agent_level_price_' . $request->num . '_' . $lrow . '_0[]" class="form-control" value="' . $in_price . '">';
            $result .=      '<input type="hidden" name="variation_agent_level_' . $request->num . '_' . $lrow . '_0[]" value="' . $agentlvl->id . '" class="form-control">';
            $result .=      '<input type="hidden" name="variation_agent_level_id_' . $request->num . '_' . $lrow . '_0[]" value="' . $in_ids . '" class="form-control">';
            $result .=  '</td>
                                  <td>
                                      <input type="text" name="agent_level_special_price_' . $request->num . '_' . $lrow . '_0[]" class="form-control" value="' . $in_special_price . '">';
            $result .=  '</td>';
          }
          $result .= '</tr>';

          $lrow++;
        }
      }
    } else {
      $result .=  '<tr data-id="0">
                            <td class="variation_option_display_0 first_variation" data-id="0">';
      $result .=        'Option
                              <input type="hidden" name="rid_0[]" value="">
                            </td>
                            <td class="variation_option_two_display_0 variation_two" style="display: none;">';
      $result .=        '<span>Option</span>
                              <input type="hidden" class="variation_option_two_value_0" name="variation_option_two_value_0[]">
                            </td>
                            <td><input type="text" name="customer_price_0[]" class="form-control"></td>
                            <td><input type="text" name="customer_special_price_0[]" class="form-control"></td>';
      foreach ($agent_levels as $key => $agentlvl) {
        $result .=  '<td>
                                <input type="text" name="agent_level_price_0_0[]" class="form-control">
                                <input type="hidden" name="variation_agent_level_0_0[]" value="' . $agentlvl->id . '" class="form-control">';
        $result .=      '<input type="hidden" name="variation_agent_level_id_0_0[]" value="" class="form-control">
                            </td>
                            <td>
                                <input type="text" name="agent_level_special_price_0_0[]" class="form-control"  value="">
                            </td>';
      }

      $result .=      '<td><input type="text" name="weight_0[]" class="form-control"></td>
                            <td><input type="text" name="stock_0[]" class="form-control"></td>
                        </tr>';
    }

    $result .= '</table>
                    </div>
                    </div>';

    return $result;
  }

  public function getOptionDetail(Request $request)
  {
    $variation = ProductVariation::find($request->vid);
    $product = Product::find($variation->product_id);
    if ($product->second_variation_enable == 1) {
      $second_variations = ProductSecondVariation::where('variation_id', $request->vid)->get();

      $result = '<select class="form-control second_variation_option" name="second_variation_option' . $request->num . '" data-filter="' . $request->num . '">
                          <option value="">Select Option</option>';
      foreach ($second_variations as $second_variation) {
        $result .= '<option value="' . $second_variation->id . '">' . $second_variation->variation_name . '</option>';
      }
      $result .= '</select>';


      return $result;
    } else {
      if (!empty($variation->id)) {
        return $variation;
      } else {
        return 0;
      }
    }
  }

  public function getSecondOptionDetail(Request $request)
  {
    $product = ProductSecondVariation::find($request->vid);

    if (!empty($product->id)) {
      return $product;
    } else {
      return 0;
    }
  }

  public function GetSubCategory(Request $request)
  {
    $subs = SubCategory::where('category_id', $request->cid)->get();

    $select = '<select class="form-control sub_category_id" name="sub_category_id">';
    $select .= "<option value=''>Select Subcategory</option>";
    foreach ($subs as $sub) {
      $select .= "<option value='" . $sub->id . "'>" . $sub->sub_category_name . "</option>";
    }
    $select .= "</select>";

    return $select;
  }

  public function change_topup_action(Request $request)
  {
    try {
      \DB::beginTransaction();

      $topup = TopupTransaction::find($request->tid);
      $topup->status = $request->action_id;
      $topup->save();

      $topup_bonus_pv = GlobalController::topup_bonus_pv($topup->topup_no);
      if ($topup_bonus_pv != 'ok') {
        throw new \Exception($topup_bonus_pv);
      }

      \DB::commit();
      return "ok";
    } catch (\Exception $e) {
      \DB::rollback();
      return $e->getMessage() . ' - ' . $e->getLine();
    } catch (\Error $e) {
      \DB::rollback();
      return $e->getMessage() . ' - ' . $e->getLine();
    }
  }

  public function cashRebate($user_id, $totalAmount)
  {

    $affiliates = Affiliate::select('affiliates.*', 'm.lvl as upline_lvl', 'user_id as m_user_id', 'm.created_at as m_created_at')
      ->join('merchants as m', 'm.code', 'affiliates.user_id')
      ->where('affiliate_id', $user_id)
      ->where('user_id', '!=', 'AD000001')
      ->where('m.status', '1')
      ->groupBy('m.code')
      ->get();

    $mer = Merchant::select('merchants.*', 'lvl as upline_lvl', 'code as m_user_id', 'created_at as m_created_at')
      ->where('code', $user_id)
      ->get();

    $all = $affiliates->concat($mer);
    $all = array_reverse(array_sort($all, function ($value) {
      return $value['m_created_at'];
    }));

    foreach ($all as $affiliate) {
      if (!empty($affiliate->upline_lvl)) {
        $SettingMerchantRebate = SettingMerchantRebate::where('agent_lvl', $affiliate->upline_lvl)->first();
        if (isset($currentM)) {
          if ($currentM != $affiliate->m_user_id) {
            if (!empty($current_comm)) {
              $pay_comm = $SettingMerchantRebate->amount - $current_comm;
              if ($pay_comm <= 0) {
                $notsame = $current_comm;
                $current_comm = $current_comm;
                continue;
              } else {
                $notsame = $pay_comm;
                $current_comm = $SettingMerchantRebate->amount;
              }
            } else {
              $pay_comm = $SettingMerchantRebate->amount;
            }
            $currentM = $affiliate->m_user_id;
          } else {
            if (isset($notsame)) {
              $pay_comm = $notsame;
            } else {
              $pay_comm = $SettingMerchantRebate->amount;
            }
          }
        } else {
          $currentM = $affiliate->m_user_id;
          if (!empty($SettingMerchantRebate->amount)) {
            $current_comm = $SettingMerchantRebate->amount;
            $pay_comm = $SettingMerchantRebate->amount;
          }
        }

        if (isset($pay_comm) && $pay_comm > 0) {
          AffiliateCommission::create([
            'type' => '8',
            'user_id' => $affiliate->m_user_id,
            'product_amount' => $totalAmount,
            'comm_pa_type' => 'Percentage',
            'comm_pa' => $pay_comm,
            'comm_amount' => ($totalAmount * $pay_comm / 100),
            'comm_desc' => 'Cash Rebate From #' . $user_id
          ]);
        }
      }
    }
    // exit();

    //Extra 5%
    $merchant = Merchant::where('code', $user_id)->first();
    $upline = Merchant::where('code', $merchant->master_id)->first();

    if ($merchant->lvl >= 4) {
      if ($upline->lvl == $merchant->lvl) {
        AffiliateCommission::create([
          'type' => '99',
          'user_id' => $upline->code,
          'product_amount' => $totalAmount,
          'comm_pa_type' => 'Percentage',
          'comm_pa' => 5,
          'comm_amount' => ($totalAmount * 5 / 100),
          'comm_desc' => 'Extra Cash Rebate From #' . $user_id,
          'status' => '99'
        ]);
      }
    }
  }

  public function DeleteShipping(Request $request)
  {
    $delete = SettingShippingFee::find($request->sid)->delete();
  }

  public function courier_service_list(Request $request)
  {
    $transaction = Transaction::find($request->tid);

    $pickA = SettingPickUpAddress::where('status', '1')->first();

    if (empty($pickA->id)) {
      return "pick up address error";
    }
    $weight = $request->weight;

    $country = 'MY';
    $pick_code = $pickA->postcode;
    $pick_state = $pickA->state;

    $send_code = $transaction->postcode;
    $send_state = $transaction->state;


    $domain = "http://connect.easyparcel.my/?ac=";

    $action = "EPRateCheckingBulk";
    $postparam = array(
      'api'   => 'EP-QLTip0ZGl',
      'bulk'  => array(
        array(
          'pick_code' => $pick_code,
          'pick_state'    => $pick_state,
          'pick_country'  => $country,
          'send_code' => $send_code,
          'send_state'    => $send_state,
          'send_country'  => $country,
          'weight'    => $weight,
          'width' => '0',
          'length'    => '0',
          'height'    => '0',
          'date_coll' => date('Y-m-d'),
        ),
      ),
      'exclude_fields'  => array(
        'pgeon_point',
      )
    );

    $url = $domain . $action;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postparam));
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

    ob_start();
    $return = curl_exec($ch);
    ob_end_clean();
    curl_close($ch);

    $json = json_decode($return);

    $displayRates = "<table class='table'>
                            <tr>
                                <td>Select</td>
                                <td colspan='2'>Courier Company & Shipping Fee</td>
                                <td>Scheduled parcel delivery</td>
                                <td>Delivery date</td>
                            </tr>";
    foreach ($json->result as $value) {
      if ($value->status == 'Success') {
        foreach ($value->rates as $key => $value2) {
          if ($value2->courier_name == 'Pgeon' && $value2->service_detail == 'pickup') {
            $checked = ($key == 0) ? 'checked' : '';

            $displayRates .= "<tr>
                                                <td>
                                                    <input type='hidden' name='tid' value='" . $request->tid . "'>
                                                    <input type='hidden' name='rowid' value='" . $request->row . "'>
                                                    <input type='hidden' name='Inweight' value='" . $request->weight . "'>
                                                    <input type='hidden' name='collect_date' value='" . $value2->pickup_date . "'>
                                                    <input type='hidden' name='courier_logo' value='" . $value2->courier_logo . "'>
                                                    <div class='radio'>
                                                        <label>
                                                            <input name='service_id' type='radio' class='ace' " . $checked . " value='" . $value2->service_id . "' />
                                                            <span class='lbl'></span>
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <img src='" . $value2->courier_logo . "' width='100px'>
                                                </td>
                                                <td>
                                                    " . $value2->service_id . " - " . $value2->courier_name . "
                                                    <br>
                                                    RM " . $value2->price . "
                                                    <br>
                                                    <span class='service_detail'>" . $value2->service_detail . "</span>";

            $displayRates .=        "</td>
                                                 <td>" . $value2->scheduled_start_date . "</td>
                                                 <td>" . $value2->pickup_date . "</td>
                                          </tr>";
          }
        }
      } else {

        $displayRates .= "<tr>
                                            <td align='center' colspan='5'>
                                                " . $value->remarks . "
                                            </td>
                                      </tr>";
      }
    }
    $displayRates .= "</table>";

    return $displayRates;
  }

  public function courier_make_order(Request $request)
  {
    $transaction = Transaction::find($request->tid);
    $transaction2 = Transaction::find($request->tid);
    $pickA = SettingPickUpAddress::where('status', '1')->first();
    $admin = Admin::where('id', '1')->first();

    $trim_trans_no = substr($transaction->transaction_no, 12);

    $filter_send_address = preg_replace('~[^\p{L}\p{N}\n]+~u', ',', $transaction->address);

    $service_id = $request->sid;
    $weight = $request->weight;
    $content = "Order: #" . $trim_trans_no;
    $value = $transaction->grand_total;
    $pick_name = $pick_company = $pickA->company_name;
    $pick_contact = $pick_mobile = "+60" . $pickA->contact;
    $pick_addr1 = $pickA->address;
    $pick_city = $pickA->city;
    $pick_state = $pickA->state;
    $pick_code = $pickA->postcode;
    $pick_country = $send_country = 'MY';

    $send_name = $transaction->address_name;
    $send_contact = $send_mobile = "+" . $transaction->country_code . $transaction->phone;
    // $send_addr1 = $transaction->address;
    $send_addr1 = $filter_send_address;
    $send_city = $transaction->city;
    $send_state = $transaction->state;
    $send_code = $transaction->postcode;

    $domain = "http://connect.easyparcel.my/?ac=";

    $action = "EPSubmitOrderBulk";
    $postparam = array(
      'api'   => 'EP-QLTip0ZGl',
      'bulk'  => array(
        array(
          'weight'    => $weight,
          'width' => '0',
          'length'    => '0',
          'height'    => '0',
          'content'   => $content,
          'value' => $value,
          'service_id'    => $service_id,
          'pick_point'    => '',
          'pick_name' => $pick_name,
          'pick_company'  => $pick_company,
          'pick_contact'  => $pick_contact,
          'pick_mobile'   => '',
          'pick_addr1'    => trim($pick_addr1),
          'pick_addr2'    => '',
          'pick_addr3'    => '',
          'pick_addr4'    => '',
          'pick_city' => $pick_city,
          'pick_state'    => $pick_state,
          'pick_code' => $pick_code,
          'pick_country'  => $pick_country,
          'send_point'    => '',
          'send_name' => $send_name,
          'send_company'  => '',
          'send_contact'  => $send_contact,
          'send_mobile'   => '',
          'send_addr1'    => trim($send_addr1),
          'send_addr2'    => '',
          'send_addr3'    => '',
          'send_addr4'    => '',
          'send_city' => $send_city,
          'send_state'    => $send_state,
          'send_code' => $send_code,
          'send_country'  => $send_country,
          'collect_date'  => $request->collect_date,
          'sms'   => '0',
          'send_email'    => $admin->email,
          'hs_code'   => ''
        ),
      ),
    );

    $url = $domain . $action;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postparam));
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

    ob_start();
    $return = curl_exec($ch);
    ob_end_clean();
    curl_close($ch);

    $json = json_decode($return);
    // echo "<pre>"; print_r($json); echo "</pre>";
    $update_info = [];
    foreach ($json->result as $value) {


      // $transaction = $transaction->update($update_info);

      $domain = "http://connect.easyparcel.my/?ac=";

      $action = "EPPayOrderBulk";
      $postparam = array(
        'api'   => 'EP-QLTip0ZGl',
        'bulk'  => array(
          array(
            'order_no'  => $value->order_number,
          ),
        ),
      );

      $url = $domain . $action;
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postparam));
      curl_setopt($ch, CURLOPT_HEADER, 0);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

      ob_start();
      $return = curl_exec($ch);
      ob_end_clean();
      curl_close($ch);

      $json = json_decode($return);
      // echo "<pre>"; print_r($json); echo "</pre>";
      $update_info2 = [];
      foreach ($json->result as $value2) {
        if ($value2->messagenow == 'Insufficient Credit') {
          return 2;
        }

        if ($value2->messagenow == 'Payment Done') {
          foreach ($value2->parcel as $value3)
            $update_info['row_id'] = $request->rowid;
          $update_info['transaction_id'] = $request->tid;
          $update_info['tracking_no'] = $value3->awb;
          $update_info['parcel_number'] = $value->parcel_number;
          $update_info['order_number'] = $value->order_number;
          $update_info['ep_order_price'] = $value->price;
          $update_info['courier'] = $value->courier;
          $update_info['courier_logo'] = $request->courier_logo;
          $update_info['remarks'] = $value->remarks;

          $createTracking = TransactionTracking::create($update_info);

          // $transaction2 = $transaction2->update($update_info2);
          return 1;
        }
        // return $value2->messagenow;
      }
    }
  }

  public function get_tracking_number(Request $request)
  {
    $currentTracking = TransactionTracking::where('transaction_id', $request->tid)
      ->where('status', '1')
      ->first();

    if (!empty($currentTracking)) {
      return $currentTracking->tracking_no;
    } else {
      return "This transaction does not have a tracking number";
    }
  }

  public function uploadMainPageImage(Request $request)
  {
    $select = SettingMainPage::where('status', '1')->get();

    $files = $request->file('file');
    $name = $files->getClientOriginalName();
    $exp = explode(".", $name);
    $file_ext = end($exp);
    $name = md5($name . date('Y-m-d H:i:s')) . '.' . $file_ext;

    $input = $request->all();

    $input['status'] = '1';
    $input['image'] = "uploads/banner/" . $name;

    $files->move(GlobalController::get_image_path("uploads/banner/"), $name);

    $product_image = SettingMainPage::create($input);





    $image_list = "";
    if (!$select->isEmpty()) {
      foreach ($select as $key => $value) {
        $image_list .= '<div class="product-image-thumbnail" data-id="' . $value->id . '">
                            <div class="form-group">
                              <div class="delete-image-box">
                                <a href="#" class="delete-image" data-id="' . $value->id . '">
                                  <i class="bi bi-trash"></i>
                                </a>
                              </div>
                              <div class="product-image-thumbnail-img" style="background-image: url(' . GlobalController::get_production_url($value->image) . ')"></div>
                              <div class="form-group">
                                  <input type="text" class="form-control title" name="title" placeholder="Title">
                              </div>
                              <div class="form-group">
                                  <textarea class="form-control description" name="description" placeholder="Description"></textarea>
                              </div>
                            </div>
                          </div>';
      }
    }

    return json_encode($image_list);
  }

  public function LoadMainPageImage()
  {
    $select = SettingMainPage::where('status', '1')->get();

    $image_list = "";
    if (!$select->isEmpty()) {
      foreach ($select as $key => $value) {
        $image_list .= '<div class="product-image-thumbnail" data-id="' . $value->id . '">
                            <div class="form-group">
                              <div class="delete-image-box">
                                <a href="#" class="delete-image" data-id="' . $value->id . '">
                                  <i class="bi bi-trash"></i>
                                </a>
                              </div>
                              <div class="product-image-thumbnail-img" style="background-image: url(' . GlobalController::get_production_url($value->image) . ')"></div>
                              <div class="form-group">
                                  <input type="text" class="form-control title" name="title" placeholder="Title" value="' . $value->title . '">
                              </div>
                              <div class="form-group">
                                  <textarea class="form-control description" name="description" placeholder="Description">' . $value->description . '</textarea>
                              </div>
                            </div>
                          </div>';
      }
    }

    return $image_list;
  }

  public function DeleteMainPageImage($id)
  {
    $delete = SettingMainPage::find($id);
    File::delete($delete->image);
    $delete = $delete->delete();
  }

  public function updateTitle(Request $request)
  {
    $main_page = SettingMainPage::find($request->mid);
    $main_page = $main_page->update(['title' => $request->title]);
  }

  public function updateDescription(Request $request)
  {
    $main_page = SettingMainPage::find($request->mid);
    $main_page = $main_page->update(['description' => $request->description]);
  }

  public function SortMainImage(Request $request)
  {
    $numbers = explode(',', $request->number);
    $mid = explode(',', $request->mid);

    foreach ($numbers as $key => $number) {
      $images = SettingMainPage::find($mid[$key]);
      $images = $images->update(['sort_level' => $number]);
    }
  }

  public function PvBalance($user_id)
  {
    $balance = AffiliateCommission::select(DB::raw('SUM(comm_amount) as totalBalance'))
      ->where('user_id', $user_id)
      ->where('status', '1')
      ->where('type', '!=', '7')
      ->whereNull('claimed')
      ->whereNull('burned')
      ->first();
    $totalBalance = 0;

    $totalBalance = $balance->totalBalance;


    return $totalBalance;
  }

  public function setAdminBankDefault(Request $request)
  {
    $clearDefault = BankAccount::where('user_id', Auth::user()->code)->update(['default_banks' => NULL]);

    $setDefault = BankAccount::where("id", $request->bid);
    $setDefault = $setDefault->update(['default_banks' => '1']);

    $getDefault = BankAccount::where('default_banks', '1')
      ->where('user_id',  Auth::user()->code)
      ->first();

    return array($getDefault->bank_name, $getDefault->bank_holder_name, $getDefault->bank_account);
  }

  public function sortingProduct(Request $request)
  {
    $product = Product::where('sorting', $request->sorting)
      ->where('status', '1')
      ->where('sorting', '<>', '')
      ->first();

    if (!empty($product->id)) {
      return 1;
    }

    $update = Product::find($request->id);
    $update = $update->update(['sorting' => $request->sorting]);
  }

  protected function MerchantDisplayCode($agent_lvl_code)
  {
    if ($agent_lvl_code == 'KCA') {
      $user = Merchant::select(DB::raw("COUNT(id) AS totalUser"))->first();
    } else {
      $user = Merchant::select(DB::raw("COUNT(id) AS totalUser"))->where('display_code', $agent_lvl_code)->first();
    }
    $totalCount = $user->totalUser + 1;

    if (strlen($totalCount) == '1') {
      $member_id = "00000" . $totalCount;
    } elseif (strlen($totalCount) == '2') {
      $member_id = "0000" . $totalCount;
    } elseif (strlen($totalCount) == '3') {
      $member_id = "000" . $totalCount;
    } elseif (strlen($totalCount) == '4') {
      $member_id = "00" . $totalCount;
    } elseif (strlen($totalCount) == '5') {
      $member_id = "0" . $totalCount;
    } else {
      $member_id = $totalCount;
    }

    return array($agent_lvl_code, $member_id);
  }

  public function updatePassword(Request $request)
  {
    $merchant = Merchant::find($request->mid);
    if (!empty($merchant->ic)) {

      $newPassword = substr($merchant->ic, -6);
    } else {
      $newPassword = substr($merchant->company_registration_no, -6);
    }
    $update = Merchant::find($merchant->id)->update(['password' => Hash::make($newPassword)]);
    $this->NewPasswordMessage($merchant->email, 'noreply@kimcafe.com.my', $merchant->f_name, "Password has been reset.", $merchant, $newPassword);
  }

  public function updateMemberPassword(Request $request)
  {
    $user = User::find($request->mid);
    $newPassword = substr($user->ic, -6);
    $update = User::find($user->id)->update(['password' => Hash::make($newPassword)]);
    $this->NewPasswordMessage($user->email, 'noreply@kimcafe.com.my', $user->f_name, "Password has been reset.", $user, $newPassword);
  }

  public function NewPasswordMessage($to, $from, $name, $subject, $user, $password)
  {
    $headers = "From: $from";
    $headers = "From: " . $from . "\r\n";
    $headers .= "Reply-To: " . $from . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    // $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8";
    $headers .= '<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">';

    // $subject = "Testing.";


    $link = 'www.weshare.my';

    $body = "<!DOCTYPE html><html lang='en'><head><meta charset='UTF-8'><title></title></head><body>";
    $body .= "<table style='width: 100%;'>";
    $body .= "<thead style='text-align: center;'><tr><td style='border:none;' colspan='2'>";

    $body .= "</td></tr></thead><tbody><tr>";
    $body .= "<td style='border:none;'><strong>WELCOME TO KIM CAFE E-CORMMERCE!</strong></td></tr>";
    $body .= "<tr>
                    <td style='border:none;'>
                      <strong>Dear " . $user->f_name . "</strong>
                    </td>
                  </tr>";
    $body .= "<tr><td>Your Password has been reset, below is your informaion</td></tr>
                  <tr><td>您的密码已被重置, 以下是您的资料</td></tr>";
    $body .= "<tr><td>名字: " . $user->f_name . "</td></tr>";
    $body .= "<tr><td>电子邮件: " . $user->email . "</td></tr>";
    $body .= "<tr><td>身份证号码: " . $user->ic . "</td></tr>";
    $body .= "<tr><td>新密码 : " . $password . "</td></tr>";
    $body .= "<tr><td></td></tr>";
    $body .= "<tr><td></td></tr>";
    $body .= "<tr><td>Regards,</td></tr>";
    $body .= "<tr><td>KIM CAFE E-CORMMERCE</td></tr>";
    $body .= "<tr><td></td></tr>";
    // $body .= "<tr><td colspan='2' style='border:none;'>{$cmessage}</td></tr>";
    $body .= "</tbody></table>";
    $body .= "</body></html>";

    $send = mail($to, $subject, $body, $headers);
  }

  public function AgentUpgradeMessage($to, $from, $subject, $user, $lvl, $lvlEN, $date)
  {
    $headers = "From: $from";
    $headers = "From: " . $from . "\r\n";
    $headers .= "Reply-To: " . $from . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    // $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8";
    $headers .= '<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">';

    // $subject = "Testing.";


    $link = 'www.weshare.my';

    $body = "<!DOCTYPE html><html lang='en'><head><meta charset='UTF-8'><title></title></head><body>";
    $body .= "<table style='width: 100%;'>";
    $body .= "<thead style='text-align: center;'><tr><td style='border:none;' colspan='2'>";

    $body .= "</td></tr></thead><tbody><tr>";
    $body .= "<td style='border:none;'><strong>WELCOME TO KIM CAFE E-CORMMERCE!</strong></td></tr>";
    $body .= "<tr>
                    <td style='border:none;'>
                      <strong>Dear " . $user->f_name . "</strong>
                    </td>
                  </tr>";
    $body .= "<tr><td>Your account has been promoted to " . $lvlEN . " on " . $date . "!</td></tr>
                  <tr><td>您的户口在" . $date . "已被升职为" . $lvl . "</td></tr>";
    $body .= "<tr><td></td></tr>";
    $body .= "<tr><td></td></tr>";
    $body .= "<tr><td>Regards,</td></tr>";
    $body .= "<tr><td>KIM CAFE E-CORMMERCE</td></tr>";
    $body .= "<tr><td></td></tr>";
    // $body .= "<tr><td colspan='2' style='border:none;'>{$cmessage}</td></tr>";
    $body .= "</tbody></table>";
    $body .= "</body></html>";

    $send = mail($to, $subject, $body, $headers);
  }

  public function deleteVariation(Request $request)
  {
    ProductVariation::find($request->data_id)->delete();
    $product = Product::find($request->product_id);

    if ($product->second_variation_enable == 1) {
      ProductSecondVariation::where('variation_id', $request->data_id)->delete();
    }
  }

  public function deleteSecondVariation(Request $request)
  {
    ProductSecondVariation::where('variation_name', $request->variation_name)
      ->where('product_id', $request->product_id)
      ->delete();

    $find = ProductSecondVariation::where('product_id', $request->product_id)->get();

    if (count($find) <= 0) {
      Product::find($request->product_id)->update(['second_variation_enable' => "0"]);
    }
  }

  public function getVariationStock(Request $request)
  {

    $quantityAmount = ProductVariation::find($request->vid);

    $product = Product::find($quantityAmount->product_id);

    $getSecond_variations = ProductSecondVariation::where('product_id', $quantityAmount->product_id)
      ->where('variation_id', $request->vid)
      ->get();

    $product_v_two_list = "";

    if ($product->second_variation_enable == 1) {
      $product_v_two_list .= '<label>Second Variation</label>
                                <select class="form-control product_second_variation_option" name="product_second_variation_option' . $request->num . '">
                                  <option value="">Select Second Variation</option>';
      foreach ($getSecond_variations as $value) {
        $product_v_two_list .= '<option value="' . $value->id . '">
                                      ' . $value->variation_name . '
                                    </option>';
      }

      $product_v_two_list .= '</select>';

      return array(1, $product_v_two_list, 0);
    } else {
      $transaction = TransactionDetail::select(DB::raw('SUM(quantity) AS TransCart'))
        ->join('transactions AS t', 't.id', 'transaction_details.transaction_id')
        ->whereIn('t.status', ['1', '97', '98', '99'])
        ->where('variation_id', $request->vid)
        ->whereNull('transaction_type')
        ->first();
      $variation_balance_quantity = GlobalController::variation_balance_quantity($request->vid);
      return array(2, $variation_balance_quantity);
    }
  }

  public function getSecondVariationStock(Request $request)
  {
      $second_variation = ProductSecondVariation::find($request->svid);
      $second_variation_balance_quantity = GlobalController::second_variation_balance_quantity($second_variation->id);
      return array(2, $second_variation_balance_quantity);
  }

  public function getShippingAddress(Request $request)
  {
    $userAddress = UserShippingAddress::where('user_id', $request->mid)->where('default', '1')->first();

    if (!empty($userAddress->id)) {
      return array($userAddress->state, $userAddress->country);
    }
  }

  public function get_agent_address($user_id)
  {
    if (!empty($user_id)) {
      $address = UserShippingAddress::where('user_id', $user_id)->where('default', '1')->first();
      if (!empty($address->id)) {
        return array(
          $address->f_name, // 0
          $address->email,    // 1
          $address->country_code, // 2
          $address->phone,    //3
          $address->country,  //4
          $address->address,  //5
          $address->state,    //6
          $address->city, //7
          $address->postcode, //8
        );
      }
    }
  }

  public function get_transaction_detail(Request $request)
  {
      $product = Product::where('id', $request->pid)->first();
      $merchant = Agent::where('code', $request->agent)->first();
      $lvl = !empty($merchant->lvl) ? $merchant->lvl : '0';

      $price = 0;
      $weight = 0;
      if (!empty($product->id)) {
          if (!empty($product->variation_enable) && !empty($product->second_variation_enable)) {
              
              $agentPrice = AgentPrice::where('product_id', $product->id)
                                      ->where('variation_id', $request->vid)
                                      ->where('second_variation_id', $request->svid)
                                      ->where('agent_lvl_id', $lvl)
                                      ->first();

              if (!empty($agentPrice->id)) {
                  $price = !empty($agentPrice->special_price) ? $agentPrice->special_price : $agentPrice->price;
              }

              $product_second_variation = ProductSecondVariation::find($request->svid);
              if (!empty($product_second_variation->id)) {
                  $weight = $product_second_variation->variation_weight;
              }
          } elseif (!empty($product->variation_enable) && empty($product->second_variation_enable)) {

              $agentPrice = AgentPrice::where('product_id', $product->id)->where('variation_id', $request->vid)->where('agent_lvl_id', $lvl)->first();

              if (!empty($agentPrice->id)) {
                  $price = !empty($agentPrice->special_price) ? $agentPrice->special_price : $agentPrice->price;
              }

              $product_variation = ProductVariation::find($request->vid);
              if (!empty($product_variation->id)) {
                  $weight = $product_variation->variation_weight;
              }
          } else {
              $agentPrice = AgentPrice::where('product_id', $product->id)->where('agent_lvl_id', $lvl)->first();
              if (!empty($agentPrice->id)) {
                $price = !empty($agentPrice->special_price) ? $agentPrice->special_price : $agentPrice->price;
              }

              $weight = $product->weight;
          }

          if (empty($price)) {
              $price = !empty($product->corporate_special_price) ? $product->corporate_special_price : $product->corporate_price;
          }
          if (empty($price)) {
              $price = !empty($product->special_price) ? $product->special_price : $product->price;
          }
      }

      return array($price, $weight, $product->free_shipping, $product->free_east_shipping, $product->free_singapore_shipping);
  }

  public function get_shipping_fee(Request $request)
  {
    $totalshipping_fees = 0;
    $actual_weight = $request->shipping_weight;
    $country = $request->country;

    if (!empty($country)) {
      if ($country == 160) {
        if ($request->state  != '11' && $request->state  != '12' && $request->state  != '15') {
          $shipping_fees = SettingShippingFee::where('area', 'west')
            ->where('weight', '<=', ceil($actual_weight))
            ->orderBy('weight', 'desc')
            ->first();
          if (!empty($shipping_fees->id)) {
            $totalshipping_fees = $shipping_fees->shipping_fee;
          }
        } else {
          $shipping_fees = SettingShippingFee::where('area', 'east')
            ->where('weight', '<=', ceil($actual_weight))
            ->orderBy('weight', 'desc')
            ->first();
          if (!empty($shipping_fees->id)) {
            $totalshipping_fees = $shipping_fees->shipping_fee;
          }
        }
      } else {

        $shipping_fees = SettingShippingFee::where('country_id', $country)
          ->where('weight', '<=', ceil($actual_weight))
          ->orderBy('weight', 'desc')
          ->first();

        if (!empty($shipping_fees->id)) {
          $totalshipping_fees = $shipping_fees->shipping_fee;
        }
      }
    }

    // if($request->state > 16){
    //   $shipping_fees = SettingShippingFee::where('area', 'sg')
    //                                      ->orderBy('weight', 'desc')
    //                                      ->where('weight', '<=', ceil($actual_weight))
    //                                      ->first();

    //   if(!empty($shipping_fees->id)){
    //     $totalshipping_fees = $shipping_fees->shipping_fee;                
    //   }
    // }elseif($request->state != '11' && $request->state != '12' && $request->state != '15'){

    //   $shipping_fees = SettingShippingFee::where('area', 'west')
    //                                      ->orderBy('weight', 'desc')
    //                                      ->where('weight', '<=', ceil($actual_weight))
    //                                      ->first();
    //   if(!empty($shipping_fees->id)){
    //     $totalshipping_fees = $shipping_fees->shipping_fee;                

    //   }

    // }else{
    //   $shipping_fees = SettingShippingFee::where('area', 'east')
    //                                      ->orderBy('weight', 'desc')
    //                                      ->where('weight', '<=', ceil($actual_weight))
    //                                      ->first();
    //   if(!empty($shipping_fees->id)){
    //     $totalshipping_fees = $shipping_fees->shipping_fee;                
    //   }
    // }
    // exit();

    $totalshipping_fees = (!empty($totalshipping_fees)) ? $totalshipping_fees : 0;

    return $totalshipping_fees;
  }

  public function getTransactionVariation(Request $request)
  {
    $product = Product::find($request->pid);
    $product_variations = ProductVariation::where('product_id', $request->pid)->get();
    $stockBalance = 0;
    $merchant = User::where('code', $request->agent)->first();

    $product_v_list = "";

    if (!$product_variations->isEmpty() && $product->variation_enable == 1) {
      $product_v_list .= '<label>Variation</label>
                                <select class="form-control product_variation_option"  name="product_variation[]">
                                  <option value="">Select Variation</option>';
      foreach ($product_variations as $value) {
        $product_v_list .= '<option value="' . $value->id . '" data-pid="' . $value->product_id . '">
                                      ' . $value->variation_name . '
                                    </option>';
      }
      $product_v_list .= '</select>';

      return array(1, $product_v_list);
    } else {
      if (!empty($product->packages))
        $stockBalance = GlobalController::balance_quantity($request->pid);

      return array(2, $stockBalance);
    }
  }

  public function uploadTestimonialImage(Request $request)
  {
    $slcs = TestimonialList::where('status', '1')->get();

    $all_slcs = count($slcs);

    if ($all_slcs < 8) {
      $files = $request->file('file');
      $name = $files->getClientOriginalName();
      $exp = explode(".", $name);
      $file_ext = end($exp);
      $name = md5($name . date('Y-m-d H:i:s')) . '.' . $file_ext;

      $input = $request->all();

      $input['status'] = '1';
      $input['image'] = "uploads/" . $name;
      $files->move(GlobalController::get_image_path("uploads/"), $name);

      $product_image = TestimonialList::create($input);


      $select = TestimonialList::where('status', '1')->get();


      $image_list = "";
      if (!$select->isEmpty()) {
        foreach ($select as $key => $value) {

          $exp = explode(".", $value->image);
          $file_ext = end($exp);

          $image_list .= '<div class="product-image-thumbnail" data-id="' . $value->id . '">
                                  <div class="form-group" style="width: 100%;">
                                    <div class="delete-image-box">
                                      <a href="#" class="delete-image" data-id="' . $value->id . '">
                                        <i class="bi bi-trash"></i>
                                      </a>
                                    </div>';
          if ($file_ext == 'mp4') {
            $image_list .= '<video id="myVideo" style="width: 100%;" >
                                    <source src="' . GlobalController::get_production_url($value->image) . '" type="video/mp4">
                                  </video>';
          } else {
            $image_list .= '<div class="product-image-thumbnail-img" style="background-image: url(' . GlobalController::get_production_url($value->image) . ')"></div>';
          }
          $image_list .= '</div>
                                </div>';
        }
      }
      return $image_list;
    } else {
      return "Max";
    }
  }

  public function LoadTestimonialImage()
  {

    $select = TestimonialList::where('status', '1')->get();

    $image_list = "";
    if (!$select->isEmpty()) {
      foreach ($select as $key => $value) {
        $exp = explode(".", $value->image);
        $file_ext = end($exp);

        $image_list .= '<div class="product-image-thumbnail" data-id="' . $value->id . '">
                            <div class="form-group" style="width: 100%;">
                              <div class="delete-image-box">
                                <a href="#" class="delete-image" data-id="' . $value->id . '">
                                  <i class="bi bi-trash"></i>
                                </a>
                              </div>';
        if ($file_ext == 'mp4') {
          $image_list .= '<video id="myVideo" style="width: 100%;" autoplay="autoplay" loop="1">
                              <source src="' . GlobalController::get_production_url($value->image) . '" type="video/mp4">
                            </video>';
        } else {
          $image_list .= '<div class="product-image-thumbnail-img" style="background-image: url(' . GlobalController::get_production_url($value->image) . ')"></div>';
        }
        $image_list .= '</div>
                          </div>';
      }
    }

    return $image_list;
  }

  public function DeleteTestimonialImage($id)
  {
    $delete = TestimonialList::find($id);
    File::delete($delete->image);
    $delete = $delete->delete();
  }

  public function deleteSalesPopup(Request $request)
  {
    SalesPopup::find($request->row_id)->delete();
  }

  public function DeleteTeamBonus(Request $request)
  {
    $delete = SettingTeamDividend::find($request->id)->delete();
  }

  public function DeleteTopupBonus(Request $request)
  {
    $delete = SettingTopup::find($request->id)->delete();
  }

  public function DeleteCodAddress(Request $request)
  {
    $delete = CodAddress::find($request->id)->delete();
  }

  public function MemberUpgrade(Request $request)
  {

    $currentUser = User::find($request->row_id);
    //   $dc = $this->MerchantDisplayCode('M');
    //   $code = $this->MerchantCode();
    $dc = GlobalController::AgentDisplayCode();
    $code = GlobalController::AgentCode();
    $newAgent = [];

    $newAgent['master_id'] = $currentUser->master_id;
    $newAgent['code'] = $code;
    $newAgent['email'] = $currentUser->email;
    $newAgent['password'] = $currentUser->password;
    $newAgent['country_code'] = $currentUser->country_code;
    $newAgent['f_name'] = $currentUser->f_name;
    $newAgent['dob'] = $currentUser->dob;
    $newAgent['phone'] = $currentUser->phone;
    $newAgent['display_code'] = $dc[0];
    $newAgent['display_running_no'] = $dc[1];
    $newAgent['verify_status'] = '1';
    $newAgent['ic'] = $currentUser->ic;
    $newAgent['gender'] = $currentUser->gender;
    $newAgent['lvl'] = '1';
    $newAgent['status'] = '1';
    $newAgent['created_at'] = date('Y-m-d H:i:s');
    $newAgent['updated_at'] = date('Y-m-d H:i:s');

    // $insert = Merchant::create($newAgent);
    $insert = Agent::create($newAgent);


    $input_record = [];
    $input_record['user_id'] = $insert->code;
    $input_record['level'] = '1';

    AgentLevelRecord::create($input_record);
    // $add_affiliates = GlobalController::add_affiliates($currentUser->code, $currentUser->master_id);

    $upgradeUser = User::find($request->row_id)->update([
      'email' => $currentUser->email . '-upgradedByAdmin-',
      'phone' => $currentUser->phone . '-upgradedByAdmin-',
      'ic' => $currentUser->ic . '-upgradedByAdmin-',
      'status' => '3', 'upgraded' => '1', 'upgraded_date' => date('Y-m-d H:i:s')
    ]);
    $updateShipping = UserShippingAddress::where('user_id', $currentUser->code)->update(['user_id' => $insert->code]);
    $updateTransaction = Transaction::where('user_id', $currentUser->code)->update(['user_id' => $insert->code]);


    $update_downlines = User::where('master_id', $currentUser->code)->get();
    foreach ($update_downlines as $update_downline) {
      User::find($update_downline->id)->update(['master_id' => $insert->code]);
    }

    // $update_downlines_m = Merchant::where('master_id', $currentUser->code)->get();
    $update_downlines_m = Agent::where('master_id', $currentUser->code)->get();
    foreach ($update_downlines_m as $update_downline_m) {
      // Merchant::find($update_downline_m->id)->update(['master_id' => $insert->code]);
      Agent::find($update_downline_m->id)->update(['master_id' => $insert->code]);
    }

    $upgrade_affiliate_user_id = Affiliate::where('user_id', $currentUser->code)->update(['user_id' => $insert->code]);
    $upgrade_affiliate_id = Affiliate::where('affiliate_id', $currentUser->code)->update(['affiliate_id' => $insert->code]);

    return response()->json([
      'new_id' => $insert->id
    ]);
  }

  public static function GenerateTransactionNo()
  {
    $year = date('Y');
    $month = date('m');
    $day = date('d');
    $hour = date('H');
    $minute = date('i');
    $combine = $year . $month . $day . $hour . $minute;
    $transaction = Transaction::select(DB::raw('COUNT(id) AS TotalTransaction'))
      ->first();
    $TotalTransaction = $transaction->TotalTransaction + 1;
    if (strlen($TotalTransaction) == 1) {
      $tNo = $combine . "0000" . $TotalTransaction;
    } elseif (strlen($TotalTransaction) == 2) {
      $tNo = $combine . "000" . $TotalTransaction;
    } elseif (strlen($TotalTransaction) == 3) {
      $tNo = $combine . "00" . $TotalTransaction;
    } elseif (strlen($TotalTransaction) == 4) {
      $tNo = $combine . "0" . $TotalTransaction;
    } else {
      $tNo = $combine . $TotalTransaction;
    }
    return $tNo;
  }

  public function uploadFeedbackImage(Request $request, $id)
  {

    $files = $request->file('file');
    $name = $files->getClientOriginalName();
    $exp = explode(".", $name);
    $file_ext = end($exp);
    $name = md5($name . date('Y-m-d H:i:s')) . '.' . $file_ext;

    $input = $request->all();
    if ($id == 0) {
      $input['product_id'] = $id;
      $input['status'] = '99';
      $input['image'] = "uploads/" . $name;

      $files->move(GlobalController::get_image_path("uploads/"), $name);
    } else {
      $input['feedback_id'] = $id;
      $input['status'] = '1';
      $input['image'] = "uploads/" . $id . "/" . $name;

      $files->move(GlobalController::get_image_path("uploads/" . $id . "/"), $name);
    }
    $product_image = SettingFeedbackDetail::create($input);


    if ($id == 0) {
      $select = SettingFeedbackDetail::where('status', '99')->get();
    } else {
      $select = SettingFeedbackDetail::where('status', '1')
        ->where('feedback_id', $id)
        ->get();
    }
    // return 123;

    $image_list = "";
    if (!$select->isEmpty()) {
      foreach ($select as $key => $value) {

        $exp = explode(".", $value->image);
        $file_ext = end($exp);

        $image_list .= '<div class="product-image-thumbnail" data-id="' . $value->id . '">
                          <div class="form-group" style="width: 100%;">
                            <div class="delete-image-box">
                              <a href="#" class="delete-image" data-id="' . $value->id . '">
                                <i class="bi bi-trash"></i>
                              </a>
                            </div>';
        if ($file_ext == 'mp4') {
          $image_list .= '<video id="myVideo" style="width: 100%;" >
                            <source src="' . GlobalController::get_production_url($value->image) . '" type="video/mp4">
                          </video>';
        } else {
          $image_list .= '<div class="product-image-thumbnail-img" style="background-image: url(' . GlobalController::get_production_url($value->image) . ')"></div>';
        }
        $image_list .= '</div>
                        </div>';
      }
    }

    // return json_encode($image_list);
    return $image_list;
  }

  public function LoadFeedbackImage($id)
  {
    if ($id == 0) {
      $select = SettingFeedbackDetail::where('status', '99')->orderBy('sort_level', 'asc')->get();
    } else {
      $select = SettingFeedbackDetail::where('status', '1')
        ->where('feedback_id', $id)
        ->get();
    }

    $image_list = "";
    if (!$select->isEmpty()) {
      foreach ($select as $key => $value) {
        $exp = explode(".", $value->image);
        $file_ext = end($exp);

        $image_list .= '<div class="product-image-thumbnail" data-id="' . $value->id . '">
                          <div class="form-group" style="width: 100%;">
                            <div class="delete-image-box">
                              <a href="#" class="delete-image" data-id="' . $value->id . '">
                                <i class="bi bi-trash"></i>
                              </a>
                            </div>';
        if ($file_ext == 'mp4') {
          $image_list .= '<video id="myVideo" style="width: 100%;" autoplay="autoplay" loop="1">
                            <source src="' . GlobalController::get_production_url($value->image) . '" type="video/mp4">
                          </video>';
        } else {
          $image_list .= '<div class="product-image-thumbnail-img" style="background-image: url(' . GlobalController::get_production_url($value->image) . ')"></div>';
        }
        $image_list .= '</div>
                        </div>';
      }
    }

    return $image_list;
  }

  public function DeleteFeedBackImage($id)
  {
    $delete = SettingFeedbackDetail::find($id);
    File::delete($delete->image);
    $delete = $delete->delete();
  }

  public function DeleteUOM(Request $request)
  {
    $delete = SettingUom::find($request->uid)->delete();
  }

  public function referral_reward_bonus()
  {
    $merchants = Merchant::whereStatus(1)->get();

    foreach ($merchants as $merchant) {
      $search_direct_downline = Merchant::select(DB::raw('COUNT(id) as totalRefferal'))->whereMasterId($merchant->code)->whereStatus(1)->first();

      // if($search_direct_downline->totalRefferal > ){

      // }
    }
  }

  public function DeletePromoDetail(Request $request)
  {
    PromoAgentItem::find($request->id)->delete();
    PromoAgentItemDetail::where('promo_item_id', $request->id)->delete();
  }

  public function Generate_Refferal_Reward($master_id)
  {
    $upline = Merchant::where('code', $master_id)->first();
    if (!empty($upline->id)) {
      $referral_reward = SettingRefferalReward::where('agent_lvl', $upline->lvl)->first();

      if (!empty($referral_reward->amount) && !empty($referral_reward->direct_downlines_no)) {
        $referral_count = !empty($referral_reward->direct_downlines_no) ? $referral_reward->direct_downlines_no : 1;
        $get_upline = Merchant::select(DB::raw('COUNT(id) as totalDownline'))->where('master_id', $master_id)->first();

        // if($get_upline->totalDownline == 5){
        if ($get_upline->totalDownline % $referral_reward->direct_downlines_no == 0) {
          $input_referral = [];
          $input_referral['type'] = 55;
          $input_referral['user_id'] = $master_id;
          $input_referral['product_amount'] = $referral_reward->amount;
          $input_referral['comm_pa_type'] = "Amount";
          $input_referral['comm_pa'] = $referral_reward->amount;
          $input_referral['comm_amount'] = $referral_reward->amount;
          $input_referral['comm_desc'] = "Referral New " . $referral_reward->direct_downlines_no . " Agent Bonus";

          AffiliateCommission::create($input_referral);
        }
      }
    }
  }

  public function change_withdrawal_stock(Request $request)
  {
    $input = $request->all();
    $input['status'] = $request->action_id;
    $select = WithdrawalStock::find($request->tid);

    $topup = WithdrawalStock::find($request->tid);
    $topup = $topup->update($input);
  }

  public function SortBanner(Request $request)
  {
    $images = SettingBanner::find($request->mid);
    $images = $images->update(['sort_level' => $request->number]);
  }

  public function getVariationAndStock(Request $request)
  {
    $product_var = ProductVariation::find($request->vid);

    if (!empty($product_var->id)) {
      $stock = GlobalController::variation_balance_quantity($product_var->id);

      return $stock;
    } else {
      return 0;
    }
  }

  public function AddonDealStatus(Request $request)
  {
    // $input = $request->all();
    // $input['status'] = $request->status;
    // if (!empty($request->status)) {
    //   if ($request->status == '3') {
    //     // Delete the row
    //     AddOnDeal::where('id', $request->row_id)->delete();
    //     AddOnDealItem::where('add_on_id', $request->row_id)->delete();
    //     AddOnDealSubItem::where('add_on_id', $request->row_id)->delete();

    //     return;
    //   } else {
    //     // Update the row
    //     $table = AddOnDeal::find($request->row_id);
    //     $table->update($input);

    //     $Itemupdate1 = AddOnDealItem::where('add_on_id', $request->row_id);
    //     $Itemupdate1->update(['status' => $request->status]);

    //     $Itemupdate2 = AddOnDealSubItem::where('add_on_id', $request->row_id);
    //     $Itemupdate2->update(['status' => $request->status]);
    //   }
    // }
    // $table = AddOnDeal::find($request->row_id);
    // $table = $table->update($input);

      try {
          \DB::beginTransaction();

          if ($request->status == '3') {
              AddOnDeal::where('id', $request->row_id)->delete();
              AddOnDealItem::where('add_on_id', $request->row_id)->delete();
              AddOnDealSubItem::where('add_on_id', $request->row_id)->delete();
          } else {
              $add_on_deal = AddOnDeal::find($request->row_id);
              $add_on_deal->status = $request->status;

              if ($request->status == '1') {
                  if (date('Y-m-d H:i:s') > $add_on_deal->end_date) {
                      throw new \Exception('PWP Has Ended On '.$add_on_deal->end_date.'. Please Adjust The End Date To Reactivate');
                  }
              }

              $add_on_deal->save();

              $Itemupdate1 = AddOnDealItem::where('add_on_id', $request->row_id)->get();
              foreach ($Itemupdate1 as $itemupdate1) {
                  $itemupdate1->status = $request->status;
                  $itemupdate1->save();
              }

              $Itemupdate2 = AddOnDealSubItem::where('add_on_id', $request->row_id)->get();
              foreach ($Itemupdate2 as $itemupdate2) {
                  $itemupdate2->status = $request->status;
                  $itemupdate2->save();
              }
          }

          \DB::commit();

          return "ok";
      } catch (\Exception $e) {
          \DB::rollback();
          return $e->getMessage().' - '.$e->getLine();
      } catch (\Error $e) {
          \DB::rollback();
          return $e->getMessage().' - '.$e->getLine();
      }
  }

  public function product_listing(Request $request)
  {
    try {
      $html = '';
      $variation_price_check = '';
      $products = Product::where('status', '1');

      $included_product_array = [];
      $included_product_variation_array = [];
      $included_product_second_variation_array = [];
      if (!empty($request->add_on_id)) {
        $included_products = AddOnDealItem::where('add_on_id', $request->add_on_id)
          ->where('status', '1')
          ->get();

        $included_product_array = $included_products->pluck('product_id')->toArray();

        $included_product_variation_array = $included_products->pluck('variation_id')->toArray();

        $included_product_second_variation_array = $included_products->pluck('second_variation_id')->toArray();
      } else {
        $included_products = AddOnDealItem::where('add_on_id', '0')
          ->where('status', '99')
          ->get();

        $included_product_array = $included_products->pluck('product_id')->toArray();

        $included_product_variation_array = $included_products->pluck('variation_id')->toArray();

        $included_product_second_variation_array = $included_products->pluck('second_variation_id')->toArray();
      }

      $products = $products->get();

      $not_included_variations = NULL;
      $not_included_second_variations = NULL;

      foreach ($products as $key => $product) {
        if (!empty($p->variation_enable)) {
          $not_included_variations = ProductVariation::where('product_id', $product->id)
            ->where('status', '1')
            ->get();

          $not_included_second_variations = ProductSecondVariation::where('product_id', $product->id)
            ->whereNotIn('id', $included_product_second_variation_array)
            ->where('status', '1')
            ->get();

          foreach ($not_included_variations as $vkey => $not_included_variation) {
            $get_all_not_included_second_variations = $not_included_second_variations->pluck('variation_id')->toArray();

            if (!empty($get_all_not_included_second_variations)) {
              if (!in_array($not_included_variation->id, $get_all_not_included_second_variations)) {
                // $not_included_variation->forget($vkey);
                unset($not_included_variations[$vkey]);
              }
            }
          }

          $get_all_not_included_variations = $not_included_variations->pluck('product_id')->toArray();

          if (!empty($get_all_not_included_variations)) {
            if (!in_array($product->id, $get_all_not_included_variations)) {
              // $product->forget($key);
              unset($products[$key]);
            }
          }
        } else {
          if (!empty($included_product_array)) {
            if (in_array($product->id, $included_product_array)) {
              // $product->forget($key);
              unset($products[$key]);
            }
          }
        }
      }

      $num = 0;
      $row = 0;
      $item_num  = 0;
      $data = GlobalController::get_translations();
      foreach ($products as $key => $p) {
        $html .= '<tr class="add_on_listing" >';
        $html .= '<td><input type="checkbox" name="product_check[]" id="item_' . $item_num . '" class="product_check" data-id="' . $p->id . '"></td>';
        $html .= '<td>' . $p->product_name . '';
        if (!empty($p->variation_enable)) {
          $variations = ProductVariation::where('product_id', $p->id)->where('status', '1')->get();

          if (!$variations->isEmpty()) {
            $html .= '<div class="row">';
            foreach ($variations as $key => $variation) {
              $html .= '<div class="col-lg-12">';

              $variation_price_check = !empty($variation->variation_special_price) ? $variation->variation_special_price : $variation->variation_price;
              if (!empty($variation_price_check)) {
                $price = GlobalController::get_product_pricing(md5($p->id), "", $variation->id);

                $html .= '<br><label><input type="checkbox" name="product_variations[]"  class="product_variations" data-row="' . $variation->product_id . '" data-id="' . $variation->id . '"> &nbsp;' . $variation->variation_name . '</label>';
                $html .= '<br>&nbsp; ' . (isset($data['backendlang']['backendlang']['Available_Stock']) ? $data['backendlang']['backendlang']['Available_Stock'] : 'Available Stock') . ': ' . GlobalController::variation_balance_quantity($variation->id) . '';
                $html .= '<br>&nbsp; RM ' . number_format($price['product_price'], 2) . '';
              } else {
                $html .= '<br><label>' . (isset($data['backendlang']['backendlang']['Option']) ? $data['backendlang']['backendlang']['Option'] : 'Option') . ': ' . $variation->variation_name . '</label>';
                $html .= '<br><label><input type="checkbox" name="product_variations[]" class="product_variations" data-id="' . $variation->id . '" hidden></label>';
              }
              $second_variations = ProductSecondVariation::where('variation_id', $variation->id)->where('status', '1')->get();
              if (!$second_variations->isEmpty()) {
                foreach ($second_variations as $sv) {
                  $price = GlobalController::get_product_pricing(md5($p->id), "", $variation->id, $sv->id);

                  $html .= '<br><label><input type="checkbox" name="check_product_sec_variation[]" class="check_product_sec_variation"  data-row="' . $sv->product_id . '"   id="' . $sv->variation_id . '" data-id="' . $sv->id . '"> &nbsp;' . $sv->variation_name . '</label>';
                  $html .= '<br>&nbsp; ' . (isset($data['backendlang']['backendlang']['Available_Stock']) ? $data['backendlang']['backendlang']['Available_Stock'] : 'Available Stock') . ': ' . GlobalController::second_variation_balance_quantity($sv->id) . '';
                  $html .= '<br>&nbsp; RM ' . number_format($price['product_price'], 2) . '';
                }
              }
              $html .= '</div>';
            }
          }
          $html .= '</div>';
        } else {
          $price = GlobalController::get_product_pricing(md5($p->id));

          $html .= '<br>&nbsp; ' . (isset($data['backendlang']['backendlang']['Available_Stock']) ? $data['backendlang']['backendlang']['Available_Stock'] : 'Available Stock') . ': ' . GlobalController::balance_quantity($p->id) . '';
          $html .= '<br>&nbsp; RM ' . number_format($price['product_price'], 2) . '';
        }
        $html .= '</td>';
        $html .= '<tr>';
      }
      return $html;
    } catch (Throwable $e) {
      $message = $e->getMessage();
      return $message;
    }
  }

  public function add_on_product_listing(Request $request)
  {
    $html = '';
    // $add_on = [];

    // $item = AddOnDealItem::where('status','99')->get();
    // foreach($item as $items){
    //   $add_on[] = [$items->product_id];
    // }


    // if (empty($add_on)) {
    //    $product = Product::where('status','1')->get();

    // }else{
    //      $product = Product::where('status','1')->whereNotIn('id',$add_on)->get();
    // }
    $products = Product::where('status', '1');

    $included_product_array = [];
    $included_product_variation_array = [];
    $included_product_second_variation_array = [];
    if (!empty($request->add_on_id)) {
      $included_products = AddOnDealSubItem::where('add_on_id', $request->add_on_id)
        ->where('status', '1')
        ->get();

      $included_product_array = $included_products->pluck('product_id')->toArray();

      $included_product_variation_array = $included_products->pluck('variation_id')->toArray();

      $included_product_second_variation_array = $included_products->pluck('second_variation_id')->toArray();
    } else {
      $included_products = AddOnDealSubItem::where('add_on_id', '0')
        ->where('status', '99')
        ->get();

      $included_product_array = $included_products->pluck('product_id')->toArray();

      $included_product_variation_array = $included_products->pluck('variation_id')->toArray();

      $included_product_second_variation_array = $included_products->pluck('second_variation_id')->toArray();
    }

    $products = $products->get();

    $not_included_variations = NULL;
    $not_included_second_variations = NULL;

    foreach ($products as $key => $product) {
      if (!empty($p->variation_enable)) {
        $not_included_variations = ProductVariation::where('product_id', $product->id)
          ->where('status', '1')
          ->get();

        $not_included_second_variations = ProductSecondVariation::where('product_id', $product->id)
          ->whereNotIn('id', $included_product_second_variation_array)
          ->where('status', '1')
          ->get();

        foreach ($not_included_variations as $vkey => $not_included_variation) {
          $get_all_not_included_second_variations = $not_included_second_variations->pluck('variation_id')->toArray();

          if (!empty($get_all_not_included_second_variations)) {
            if (!in_array($not_included_variation->id, $get_all_not_included_second_variations)) {
              // $not_included_variation->forget($vkey);
              unset($not_included_variations[$vkey]);
            }
          }
        }

        $get_all_not_included_variations = $not_included_variations->pluck('product_id')->toArray();

        if (!empty($get_all_not_included_variations)) {
          if (!in_array($product->id, $get_all_not_included_variations)) {
            // $product->forget($key);
            unset($products[$key]);
          }
        }
      } else {
        if (!empty($included_product_array)) {
          if (in_array($product->id, $included_product_array)) {
            // $product->forget($key);
            unset($products[$key]);
          }
        }
      }
    }

    $num = 0;
    $row = 0;
    $item_num  = 0;
    $data = GlobalController::get_translations();
    foreach ($products as $key => $p) {
      $html .= '<tr class="add_on_listing" >';
      $html .= '<td><input type="checkbox" name="check_sub_items[]" id="item_' . $item_num . '" class="check_sub_items" data-id="' . $p->id . '"></td>';
      $html .= '<td>' . $p->product_name . '';
      if (!empty($p->variation_enable)) {
        $variations = ProductVariation::where('product_id', $p->id)->where('status', '1')->get();

        if (!$variations->isEmpty()) {
          $html .= '<div class="row">';
          foreach ($variations as $key => $variation) {
            $html .= '<div class="col-lg-12">';
            $variation_price_check = !empty($variation->variation_special_price) ? $variation->variation_special_price : $variation->variation_price;
            if (!empty($variation_price_check)) {
              $price = GlobalController::get_product_pricing(md5($p->id), "", $variation->id);

              $html .= '<br><label><input type="checkbox" name="check_product_variation[]"  class="check_product_variation" data-row="' . $variation->product_id . '" data-id="' . $variation->id . '"> &nbsp;' . $variation->variation_name . '</label>';
              $html .= '<br>&nbsp; ' . (isset($data['backendlang']['backendlang']['Available_Stock']) ? $data["backendlang"]["backendlang"]["Available_Stock"] : "Available Stock") . ': ' . GlobalController::variation_balance_quantity($variation->id);
              $html .= '<br>&nbsp; RM ' . number_format($price['product_price'], 2) . '';
            } else {
              $html .= '<br><label>' . (isset($data['backendlang']['backendlang']['Option']) ? $data["backendlang"]["backendlang"]["Option"] : '') . ': ' . $variation->variation_name . '</label>';
              
              $html .= '<br><label><input type="checkbox" name="check_product_variation[]" class="check_product_variation" data-id="' . $variation->id . '" hidden></label>';
            }
            $second_variations = ProductSecondVariation::where('variation_id', $variation->id)->where('status', '1')->get();
            if (!$second_variations->isEmpty()) {
              foreach ($second_variations as $sv) {
                $price = GlobalController::get_product_pricing(md5($p->id), "", $variation->id, $sv->id);

                $html .= '<br><label><input type="checkbox" name="second_variation_id[]" class="second_variation_id"  data-row="' . $sv->product_id . '"   id="' . $sv->variation_id . '" data-id="' . $sv->id . '"> &nbsp;' . $sv->variation_name . '</label>';
                $html .= '<br>&nbsp; ' . (isset($data['backendlang']['backendlang']['Available_Stock']) ? $data["backendlang"]["backendlang"]["Available_Stock"] : "Available Stock") . ': ' . GlobalController::second_variation_balance_quantity($sv->id) . '';
                $html .= '<br>&nbsp; RM ' . number_format($price['product_price'], 2) . '';
              }
            }
            $html .= '</div>';
          }
        }
        $html .= '</div>';
      } else {
        $price = GlobalController::get_product_pricing(md5($p->id));

        $html .= '<br>&nbsp; ' . (isset($data['backendlang']['backendlang']['Available_Stock']) ? $data["backendlang"]["backendlang"]["Available_Stock"] : "Available Stock") . ': ' . GlobalController::balance_quantity($p->id) . '';
        $html .= '<br>&nbsp; RM ' . number_format($price['product_price'], 2) . '';
      }
      $html .= '</td>';
      $html .= '<tr>';
    }
    return $html;
  }

  public function save_add_on_deal_item(Request $request)
  {
    DB::beginTransaction();
    try {
      $add_on_id = '';

      if (!empty($request->add_on_id)) {
        $add_on_id = $request->add_on_id;
      } else {
        $add_on_id = '0';
      }
    
      if($add_on_id == 0){
        $add_on = AddOnDeal::where('status',4)->first();
        
        if(empty($add_on)){
          $add_on = new AddOnDeal();
        }
   
        $add_on->promotion_name = $request->title;

        if($request->start){
          $add_on->start_date = date('Y-m-d H:i:s', strtotime($request->start));
        }
        
        if($request->end){
          $add_on->end_date = date('Y-m-d H:i:s', strtotime($request->end));
        }

        $add_on->status = 4;

        $add_on->save();

        $add_on_id = $add_on->id;
      }

      if (!empty($request->product)) {
        $product = Product::whereIn('id', explode(',', $request->product))->where('status', '1')->get();

        foreach ($product as $key => $p) {
          // if ($p->second_variation_enable == 1) {
          //   $sec_variations = ProductSecondVariation::where('product_id', $p->id)->get();
          //   foreach ($sec_variations as $sv) {
          //     $getQtyBalance = GlobalController::second_variation_balance_quantity($sv->id);
          //     if (!empty($getQtyBalance) && $getQtyBalance > 0) {
          //       $checkExists = AddOnDealItem::where('add_on_id', $add_on_id)
          //         ->where('product_id', $p->id)
          //         ->where('variation_id', $sv->variation_id)
          //         ->where('second_variation_id', $sv->id)
          //         ->where('status', '1')
          //         ->first();

          //       if (empty($checkExists->id)) {
          //         $addondeal = new AddOnDealItem();
          //         $addondeal->add_on_id = $add_on_id;
          //         $addondeal->product_id = $p->id;
          //         $addondeal->variation_id = $sv->variation_id;
          //         $addondeal->second_variation_id = $sv->id;
          //         $addondeal->status = !empty($add_on_id) ? '1' : '99';

          //         $addondeal->save();
          //       }
          //     } else {
          //       return array('status' => '97', 'msg' => 'Quantity stock of ' . $p->product_name . ', Variation: ' . $sv->get_variation->variation_name . ', Option: ' . $sv->variation_name . ' not enough.');
          //     }
          //   }
          // } elseif ($p->variation_enable == 1) {
          //   $variations = ProductVariation::where('product_id', $p->id)->get();
          //   foreach ($variations as $variation) {
          //     $getQtyBalance = GlobalController::variation_balance_quantity($variation->id);
          //     if (!empty($getQtyBalance) && $getQtyBalance > 0) {
          //       $checkExists = AddOnDealItem::where('add_on_id', $add_on_id)
          //         ->where('product_id', $p->id)
          //         ->where('variation_id', $variation->vid)
          //         ->whereNull('second_variation_id')
          //         ->where('status', '1')
          //         ->first();

          //       if (empty($checkExists->id)) {
          //         $addondeal = new AddOnDealItem();
          //         $addondeal->add_on_id = $add_on_id;
          //         $addondeal->product_id = $p->id;
          //         $addondeal->variation_id = $variation->id;
          //         $addondeal->status = !empty($request->add_on_id) ? '1' : '99';

          //         $addondeal->save();
          //       }
          //     } else {
          //       return array('status' => '97', 'msg' => 'Quantity stock of ' . $p->product_name . ', Variation: ' . $variation->variation_name . ' not enough.');
          //     }
          //   }
          // } else {
          //   $getQtyBalance = GlobalController::balance_quantity($p->id);
          //   if (!empty($getQtyBalance) && $getQtyBalance > 0) {
          //     $checkExists = AddOnDealItem::where('add_on_id', $add_on_id)
          //       ->where('product_id', $p->id)
          //       ->whereNull('variation_id')
          //       ->whereNull('second_variation_id')
          //       ->where('status', '1')
          //       ->first();

          //     if (empty($checkExists->id)) {
          //       $addondeal = new AddOnDealItem();
          //       $addondeal->add_on_id = $add_on_id;
          //       $addondeal->product_id = $p->id;
          //       $addondeal->status = !empty($request->add_on_id) ? '1' : '99';

          //       $addondeal->save();
          //     }
          //   } else {
          //     return array('status' => '97', 'msg' => 'Quantity stock of ' . $p->product_name . ' not enough.' . $getQtyBalance);
          //   }
          // }

          if ($p->second_variation_enable == 1) {
            $sec_variations = ProductSecondVariation::where('product_id', $p->id)
                                                    ->whereIn('variation_id', explode(',', $request->variation))
                                                    ->whereIn('id', explode(',', $request->sec_variation))
                                                    ->get();
            foreach ($sec_variations as $sv) {
              $getQtyBalance = GlobalController::second_variation_balance_quantity($sv->id);
              if (!empty($getQtyBalance) && $getQtyBalance > 0) {
                $checkExists = AddOnDealItem::where('add_on_id', $add_on_id)
                  ->where('product_id', $p->id)
                  ->where('variation_id', $sv->variation_id)
                  ->where('second_variation_id', $sv->id)
                  ->where('status', '1')
                  ->first();

                if (empty($checkExists->id)) {
                  $addondeal = new AddOnDealItem();
                  $addondeal->add_on_id = $add_on_id;
                  $addondeal->product_id = $p->id;
                  $addondeal->variation_id = $sv->variation_id;
                  $addondeal->second_variation_id = $sv->id;
                  $addondeal->status = !empty($add_on_id) ? '1' : '99';

                  $addondeal->save();
                }
              } else {
                return array('status' => '97', 'msg' => 'Quantity stock of ' . $p->product_name . ', Variation: ' . $sv->get_variation->variation_name . ', Option: ' . $sv->variation_name . ' not enough.');
              }
            }
          } elseif ($p->variation_enable == 1) {
            $variations = ProductVariation::where('product_id', $p->id)
                                          ->whereIn('id', explode(',', $request->item_variation))
                                          ->get();
            foreach ($variations as $variation) {
              $getQtyBalance = GlobalController::variation_balance_quantity($variation->id);
              if (!empty($getQtyBalance) && $getQtyBalance > 0) {
                $checkExists = AddOnDealItem::where('add_on_id', $add_on_id)
                  ->where('product_id', $p->id)
                  ->where('variation_id', $variation->vid)
                  ->whereNull('second_variation_id')
                  ->where('status', '1')
                  ->first();

                if (empty($checkExists->id)) {
                  $addondeal = new AddOnDealItem();
                  $addondeal->add_on_id = $add_on_id;
                  $addondeal->product_id = $p->id;
                  $addondeal->variation_id = $variation->id;
                  $addondeal->status = !empty($add_on_id) ? '1' : '99';

                  $addondeal->save();
                }
              } else {
                return array('status' => '97', 'msg' => 'Quantity stock of ' . $p->product_name . ', Variation: ' . $variation->variation_name . ' not enough.');
              }
            }
          } else {
            $getQtyBalance = GlobalController::balance_quantity($p->id);
            if (!empty($getQtyBalance) && $getQtyBalance > 0) {
              $checkExists = AddOnDealItem::where('add_on_id', $add_on_id)
                ->where('product_id', $p->id)
                ->whereNull('variation_id')
                ->whereNull('second_variation_id')
                ->where('status', '1')
                ->first();

              if (empty($checkExists->id)) {
                $addondeal = new AddOnDealItem();
                $addondeal->add_on_id = $add_on_id;
                $addondeal->product_id = $p->id;
                $addondeal->status = !empty($add_on_id) ? '1' : '99';

                $addondeal->save();
              }
            } else {
              return array('status' => '97', 'msg' => 'Quantity stock of ' . $p->product_name . ' not enough.' . $getQtyBalance);
            }
          }
        }
      }
      DB::commit();

      return array('status' => '1', 'add_on_id' => !empty($request->add_on_id) ? $request->add_on_id : '');
    } catch (Throwable $e) {
      DB::rollback();
      $message = $e->getMessage();
      return $message;
    }
  }

  public function save_sub_item_deal(Request $request)
  {
    DB::beginTransaction();

    try {

      $add_on_id = !empty($request->add_on_id) ? $request->add_on_id : '';

      if(empty($add_on_id)){
        $add_on = AddOnDeal::where('status',4)->first();
        
        if(empty($add_on)){
          $add_on = new AddOnDeal();
        }
   
        $add_on->promotion_name = $request->title;

        if($request->start){
          $add_on->start_date = date('Y-m-d H:i:s', strtotime($request->start));
        }
        
        if($request->end){
          $add_on->end_date = date('Y-m-d H:i:s', strtotime($request->end));
        }

        $add_on->status = 4;

        $add_on->save();

        $add_on_id = $add_on->id;
      }

      // if (!empty($request->product)) {
      //   $product = Product::whereIn('id', explode(',', $request->product))->where('status', '1')->get();
      //   foreach ($product as $key => $p) {
      //     if (empty($p->variation_enable)) {
      //       $getQtyBalance = GlobalController::balance_quantity($p->id);
      //       if (!empty($getQtyBalance) && $getQtyBalance > 0) {
      //         $subItem = new AddOnDealSubItem;
      //         $subItem->add_on_id = $add_on_id;
      //         $subItem->product_id = $p->id;
      //         $subItem->status = !empty($add_on_id) ? '1' : '99';
      //         $subItem->created_at = date('Y-m-d H:i:s');

      //         $subItem->save();
      //       } else {
      //         return array('status' => '97', 'msg' => 'Quantity stock of ' . $p->product_name . ' not enough.');
      //       }
      //     }
      //   }
      // }

      // if (!empty($request->item_variation)) {
      //   $variation = ProductVariation::select('p.*', 'product_variations.*', 'p.id as pid', 'product_variations.id as vid')
      //     ->leftjoin('products as p', 'product_variations.product_id', 'p.id')
      //     ->whereIn('product_variations.id', explode(',', $request->item_variation))
      //     ->get();

      //   foreach ($variation as $key => $variations) {
      //     $getQtyBalance = GlobalController::variation_balance_quantity($variations->vid);
      //     if (!empty($getQtyBalance) && $getQtyBalance > 0) {
      //       $subItem = new AddOnDealSubItem;
      //       $subItem->add_on_id = $add_on_id;
      //       $subItem->product_id = $variations->pid;
      //       $subItem->variation_id = $variations->vid;
      //       $subItem->status = !empty($add_on_id) ? '1' : '99';
      //       $subItem->created_at = date('Y-m-d H:i:s');

      //       $subItem->save();
      //     } else {
      //       return array('status' => '97', 'msg' => 'Quantity stock of ' . $variation->product_name . ', Variation: ' . $variation->variation_name . ' not enough.');
      //     }
      //   }
      // }

      // if (!empty($request->second_variation)) {
      //   $sec_variation = ProductSecondVariation::select('p.*', 'pv.*', 'product_second_variations.*', 'p.id as pid', 'pv.id as pvid', 'pv.variation_name as pvName')
      //     ->leftjoin('product_variations as pv', 'product_second_variations.variation_id', 'pv.id')
      //     ->leftjoin('products as p', 'product_second_variations.product_id', 'p.id')
      //     ->whereIn('product_second_variations.id', explode(',', $request->second_variation))
      //     ->get();

      //   foreach ($sec_variation as $sv) {
      //     $getQtyBalance = GlobalController::second_variation_balance_quantity($sv->id);
      //     if (!empty($getQtyBalance) && $getQtyBalance > 0) {

      //       $addondeal = new AddOnDealSubItem;
      //       $addondeal->add_on_id = $add_on_id;
      //       $addondeal->product_id = $sv->product_id;
      //       $addondeal->variation_id = $sv->pvid;
      //       $addondeal->second_variation_id = $sv->id;
      //       $addondeal->status = !empty($request->add_on_id) ? '1' : '99';
      //       $addondeal->created_at = date('Y-m-d H:i:s');

      //       $addondeal->save();
      //     } else {
      //       return array('status' => '97', 'msg' => 'Quantity stock of ' . $sv->product_name . ', Variation: ' . $sv->pvName . ', Option: ' . $sv->variation_name . ' not enough.');
      //     }
      //   }
      // }

      if (!empty($request->product)) {
        $product = Product::whereIn('id', explode(',', $request->product))->where('status', '1')->get();

        foreach ($product as $key => $p) {
          if ($p->second_variation_enable == 1) {
            // $sec_variations = ProductSecondVariation::where('product_id', $p->id)->get();
            $sec_variations = ProductSecondVariation::where('product_id', $p->id)
                                                    ->whereIn('variation_id', explode(',', $request->variation))
                                                    ->whereIn('id', explode(',', $request->second_variation))
                                                    ->get();
            foreach ($sec_variations as $sv) {
              $getQtyBalance = GlobalController::second_variation_balance_quantity($sv->id);
              if (!empty($getQtyBalance) && $getQtyBalance > 0) {
                $checkExists = AddOnDealSubItem::where('add_on_id', $add_on_id)
                  ->where('product_id', $p->id)
                  ->where('variation_id', $sv->variation_id)
                  ->where('second_variation_id', $sv->id)
                  ->where('status', '1')
                  ->first();

                if (empty($checkExists->id)) {
                  $addondeal = new AddOnDealSubItem();
                  $addondeal->add_on_id = $add_on_id;
                  $addondeal->product_id = $p->id;
                  $addondeal->variation_id = $sv->variation_id;
                  $addondeal->second_variation_id = $sv->id;
                  $addondeal->status = !empty($add_on_id) ? '1' : '99';

                  $addondeal->save();
                }
              } else {
                return array('status' => '97', 'msg' => 'Quantity stock of ' . $p->product_name . ', Variation: ' . $sv->get_variation->variation_name . ', Option: ' . $sv->variation_name . ' not enough.');
              }
            }
          } elseif ($p->variation_enable == 1) {
            // $variations = ProductVariation::where('product_id', $p->id)->get();
            $variations = ProductVariation::where('product_id', $p->id)
                                          ->whereIn('id', explode(',', $request->item_variation))
                                          ->get();
            foreach ($variations as $variation) {
              $getQtyBalance = GlobalController::variation_balance_quantity($variation->id);
              if (!empty($getQtyBalance) && $getQtyBalance > 0) {
                $checkExists = AddOnDealSubItem::where('add_on_id', $add_on_id)
                  ->where('product_id', $p->id)
                  ->where('variation_id', $variation->vid)
                  ->whereNull('second_variation_id')
                  ->where('status', '1')
                  ->first();

                if (empty($checkExists->id)) {
                  $addondeal = new AddOnDealSubItem();
                  $addondeal->add_on_id = $add_on_id;
                  $addondeal->product_id = $p->id;
                  $addondeal->variation_id = $variation->id;
                  $addondeal->status = !empty($add_on_id) ? '1' : '99';

                  $addondeal->save();
                }
              } else {
                return array('status' => '97', 'msg' => 'Quantity stock of ' . $p->product_name . ', Variation: ' . $variation->variation_name . ' not enough.');
              }
            }
          } else {
            $getQtyBalance = GlobalController::balance_quantity($p->id);
            if (!empty($getQtyBalance) && $getQtyBalance > 0) {
              $checkExists = AddOnDealSubItem::where('add_on_id', $add_on_id)
                ->where('product_id', $p->id)
                ->whereNull('variation_id')
                ->whereNull('second_variation_id')
                ->where('status', '1')
                ->first();

              if (empty($checkExists->id)) {
                $addondeal = new AddOnDealSubItem();
                $addondeal->add_on_id = $add_on_id;
                $addondeal->product_id = $p->id;
                $addondeal->status = !empty($add_on_id) ? '1' : '99';

                $addondeal->save();
              }
            } else {
              return array('status' => '97', 'msg' => 'Quantity stock of ' . $p->product_name . ' not enough.' . $getQtyBalance);
            }
          }
        }
      }

      DB::commit();


      return array('status' => '1', 'add_on_id' => !empty($request->add_on_id) ? $request->add_on_id : '');
    } catch (Throwable $e) {
      DB::rollback();
      $message = $e->getMessage();
      return $message;
    }
  }

  public function display_deal_item(Request $request)
  {
    if (!empty($request->get('add_on_id'))) {

      $item = AddOnDealItem::select('add_on_deal_items.*', 'p.*', 'add_on_deal_items.id as sid')
        ->leftjoin('products as p', 'add_on_deal_items.product_id', 'p.id')
        ->where('add_on_deal_items.status', '1')
        ->where('add_on_id', $request->get('add_on_id'))
        ->get();
    } else {
      $item = AddOnDealItem::select('add_on_deal_items.*', 'p.*', 'add_on_deal_items.id as sid')
        ->leftjoin('products as p', 'add_on_deal_items.product_id', 'p.id')
        ->where('add_on_deal_items.status', '99')
        ->get();
    }

    $count = count($item);
    $html = '';
    $html .= '<div class="container-box" style="margin-top:20px;">';
    $html .= '<small>Total (' . $count . ') Prodcuts</small>';
    $html .= '<table class="table">';
    $html .= '<tr>
                    <th>Product</th>
                    <th></th>
                    <th>Current Price</th>
                    <th>Stock</th>
                    <th>Action</th>
                  </tr>';
    $html .= '<tbody class="display_items">';
    foreach ($item as $items) {
      $html .= '<tr>';
      $html .= '<td><img src="' . asset($items->get_product_det->first_image->image) . '" width="80" height="80">&nbsp; </td>';
      $html .= '<td>';
      $html .= '<p>' . $items->product_name . '</p>';
      if (!empty($items->variation_id)) {
        $variations = ProductVariation::where('id', $items->variation_id)->first();
        if (!empty($variations->id)) {
          $html .= '<p>Variation: ' . $variations->variation_name . '</p>';
        }
      }
      if (!empty($items->second_variation_id)) {
        $sec_variation = ProductSecondVariation::where('id', $items->second_variation_id)->first();
        if (!empty($sec_variation->id)) {
          $html .= '<p>Option: ' . $sec_variation->variation_name . '</p>';
        }
      }
      $html .= '</td>';
      if (!empty($items->variation_id)) {
        $variations = ProductVariation::where('id', $items->variation_id)->first();
        if (!empty($variations->id)) {
          $price = !empty($variations->variation_special_price) ? $variations->variation_special_price : $variations->variation_price;
          if (!empty($price)) {
            $price = $price;
          }
        } else {
          $price = !empty($items->special_price) ? $items->special_price : $items->price;
        }

        if (!empty($items->second_variation_id)) {
          $sec_variation = ProductSecondVariation::where('id', $items->second_variation_id)->where('variation_id', $items->variation_id)->first();

          if (!empty($sec_variation->id)) {
            $price = !empty($sec_variation->variation_special_price) ? $sec_variation->variation_special_price : $sec_variation->variation_price;
          }
        }
      } else {
        $price = !empty($items->special_price) ? $items->special_price : $items->price;
      }
      $html .= '<td>RM ' . number_format(!empty($price) ? $price : '0', 2) . '</td>';
      if (!empty($items->variation_id)) {
        $html .= '<td>' . GlobalController::variation_balance_quantity($items->variation_id) . '</td>';
      } else {
        $html .= '<td>' . GlobalController::balance_quantity($items->product_id) . '</td>';
      }
      $html .= '<td><a href="#" class="remove_item" data-id="' . $items->sid . '"><i class="bi bi-trash"></i></a></td>';
      $html .= '</tr>';
    }
    $html .= '</tbody>';
    $html .= '</div>';
    return $html;
  }

  public function display_sub_item(Request $request)
  {
    if (!empty($request->get('add_on_id'))) {
      $item = AddOnDealSubItem::select('add_on_deal_sub_items.*', 'p.*', 'p.id as pid', 'add_on_deal_sub_items.id as sid')
        ->leftjoin('products as p', 'add_on_deal_sub_items.product_id', 'p.id')
        ->where('add_on_deal_sub_items.status', '1')

        ->where('add_on_id', $request->get('add_on_id'))
        ->get();
    } else {
      $item = AddOnDealSubItem::select('add_on_deal_sub_items.*', 'p.*', 'p.id as pid', 'add_on_deal_sub_items.id as sid')
        ->leftjoin('products as p', 'add_on_deal_sub_items.product_id', 'p.id')
        ->where('add_on_deal_sub_items.status', '99')
        ->get();
    }


    $count = count($item);
    $discount_count = 0;
    $purchase_count = 0;
    $price_count = 0;
    $hidden_price_count = 0;
    $html = '';
    $html .= '<div class="container-box" style="margin-top:20px;">';
    $html .= '<small>Total (' . $count . ') Prodcuts</small>';
    $html .= '<table class="table">';
    $html .= '<tr>
                    <th><input type="checkbox" name="check_all_sub_items" class="check_all_sub_items"></th>
                    <th>Product</th>
                    <th></th>
                    <th>Current Price</th>
                    <th>Add-on Price</th>
                    <th>Add-on Discount</th>
                    <th>Stock</th>
                    <th>Purchase Limit</th>
                    <th>Action</th>
                  </tr>';
    $html .= '<tbody class="display_items">';
    foreach ($item as $items) {
      $html .= '<tr>';
      $html .= '<td><input type="checkbox" name="sub_item_check" class="sub_item_check" data-id="' . $items->id . '"></td>';
      $html .= '<td>';
      $html .= '<img src="' . asset(!empty($items->get_product_det->first_image->image) ? $items->get_product_det->first_image->image : '') . '" width="80" height="80">&nbsp;';
      $html .= '</td>';
      $html .= '<td>';
      $html .= '<p>' . $items->product_name . '</p>';
      if (!empty($items->variation_id)) {
        $variations = ProductVariation::where('id', $items->variation_id)->first();
        if (!empty($variations->id)) {
          $html .= '<br><p>Variation: ' . $variations->variation_name . '</p>';
          if (!empty($items->second_variation_id)) {
            $sec_variation = ProductSecondVariation::where('id', $items->second_variation_id)->first();
            if (!empty($sec_variation->id)) {
              $html .= '<br><p>Option: ' . $sec_variation->variation_name . '</p>';
            }
          }
        }
        // $html .= '123';
      }
      $html .= '</td>';
      if (!empty($items->variation_id)) {
        $variations = ProductVariation::where('id', $items->variation_id)->first();
        if (!empty($variations->id)) {
          $price = !empty($variations->variation_special_price) ? $variations->variation_special_price : $variations->variation_price;
          if (!empty($price)) {
            $price = $price;
          }
        } else {
          $price = !empty($items->special_price) ? $items->special_price : $items->price;
        }

        if (!empty($items->second_variation_id)) {
          $sec_variation = ProductSecondVariation::where('id', $items->second_variation_id)->where('variation_id', $items->variation_id)->first();

          if (!empty($sec_variation->id)) {
            $price = !empty($sec_variation->variation_special_price) ? $sec_variation->variation_special_price : $sec_variation->variation_price;
          }
        }
      } else {
        $price = !empty($items->special_price) ? $items->special_price : $items->price;
      }

      $html .= '<td>RM ' . number_format(!empty($price) ? $price : '0', 2) . '</td>';
      $html .= '<input type="hidden" name="hidden_price_count" value="' . number_format(!empty($price) ? $price : '0', 2) . '" id="hidden_price_' . $hidden_price_count++ . '">';
      if (!empty($items->add_on_discount)) {
        $add_on_price = $price - ($price * ($items->add_on_discount / 100));
        $html .= '<td><input type="text" name="add_on_price[]" value="' . $add_on_price . '" id="add_on_price_' . $price_count++ . '" class="form-control form-control-sm" onkeypress="isNumberKey(event)"></td>';
      } else {
        $html .= '<td><input type="text" name="add_on_price[]" id="add_on_price_' . $price_count++ . '" class="form-control form-control-sm" onkeypress="isNumberKey(event)"></td>';
      }




      if (!empty($items->add_on_discount)) {
        $html .= '<td><input type="text" name="add_on_discount[]" value="' . $items->add_on_discount . '"  id="add_on_discount_' . $discount_count++ . '"  class="form-control form-control-sm add_on_discount" onkeypress="isNumberKey(event)"></td>';
      } else {
        $html .= '<td><input type="text" name="add_on_discount[]"  id="add_on_discount_' . $discount_count++ . '"  class="form-control form-control-sm add_on_discount" onkeypress="isNumberKey(event)"></td>';
      }
      if (!empty($items->variation_id)) {
        $html .= '<td>' . GlobalController::variation_balance_quantity($items->variation_id) . '</td>';
      } else {
        $html .= '<td>' . GlobalController::balance_quantity($items->product_id) . '</td>';
      }

      if (!empty($items->purchase_limit)) {
        $html .= '<td><input type="text" name="purchase_limit" value="' . $items->purchase_limit . '" id="purchase_limits_' . $purchase_count++ . '"  class="form-control form-control-sm" onkeypress="isNumberKey(event)"></td>';
      } else {
        $html .= '<td><input type="text" name="purchase_limit" id="purchase_limits_' . $purchase_count++ . '"  class="form-control form-control-sm" onkeypress="isNumberKey(event)"></td>';
      }

      $html .= '<td><a href="#" class="remove_sub_item" data-id="' . $items->sid . '"><i class="bi bi-trash"></i></a></td>';
      $html .= '</tr>';
    }
    $html .= '</tbody>';
    $html .= '</div>';
    return $html;
  }

  public function update_all_sub_item(Request $request)
  {
    DB::beginTransaction();
    try {

      if (!empty($request->deal_id)) {

        if (!empty($request->discount)) {
          $add_on_discount = AddOnDealSubItem::where('status', '1')->where('add_on_id', $request->deal_id)->update(['add_on_discount' => $request->discount]);
        }

        if (!empty($request->limit)) {
          $purchase_limit = AddOnDealSubItem::where('status', '1')->where('add_on_id', $request->deal_id)->update(['purchase_limit' => $request->limit]);
        }

        $batch_select = AddOnDealSubItem::where('add_on_id', $request->deal_id)->get();
      } else {
        if (!empty($request->discount)) {
          $add_on_discount = AddOnDealSubItem::where('status', '1')->update(['add_on_discount' => $request->discount]);
        }

        if (!empty($request->limit)) {
          $purchase_limit = AddOnDealSubItem::where('status', '1')->update(['purchase_limit' => $request->limit]);
        }

        $batch_select = AddOnDealSubItem::where('status', '1')->get();
      }

      DB::commit();


      $batch_count = count($batch_select);
      $batch = [];
      foreach ($batch_select as $key => $batch) {
        $batch = [$batch->add_on_discount, $batch->purchase_limit];
      }

      return array('status' => '1', 'batch_discount' => $batch[0], 'batch_purchase_limit' => $batch[1], 'batch_count' => $batch_count);
    } catch (Throwable $e) {
      $message = $e->getMessage();
      return $message;
    }
  }

  public function update_selected_sub_item(Request $request)
  {
    DB::beginTransaction();
    try {
      if (!empty($request->product)) {

        if (!empty($request->discount)) {
          $update_selected = AddOnDealSubItem::whereIn('id', explode(',', $request->product))->update(['add_on_discount' => $request->discount]);
        }

        if (!empty($request->limit)) {
          $update_selected_limit = AddOnDealSubItem::whereIn('id', explode(',', $request->product))->update(['purchase_limit' => $request->limit]);
        }
        DB::commit();
        return 1;
      }
    } catch (Throwable $e) {
      DB::rollback();
      $message = $e->getMessage();
      return $message;
    }
  }

  public function remove_sub_items($id)
  {
    if (!empty($id)) {

      $del = AddOnDealSubItem::find($id);
      $del->delete();
      return 1;
    }
  }

  public function remove_items($id)
  {
    if (!empty($id)) {

      $del = AddOnDealItem::find($id);
      $del->delete();
      return 1;
    }
  }

  public function flash_sale_product_listing()
  {
    $html = '';
    $translation_data = GlobalController::get_translations();

    $variations = [];
    $second_variations = [];

    $items = FlashSaleProductDetail::where('flash_sale_id', '0')->get();

    $products = Product::orderBy('product_name', 'ASC');
    if (Auth::guard('merchant')->check()) {
      $products = $products->where('merchant_id', Auth::user()->code);
    }

    foreach ($items as $item) {
      $products = $products->where('id', '!=', $item->product_id);
    }

    $products = $products->where('status', '1')
      ->get();

    // if (empty($flash_sale_products)) {
    //      $product = Product::where('status','1')->get();
    // }else{
    //      $product = Product::where('status','1')->whereNotIn('id', $flash_sale_products)->get();
    // }

    $num = 0;
    $row = 0;
    $item_num  = 0;
    foreach ($products as $key => $p) {
      $html .= '<tr class="add_on_listing" >';
      $html .= '<td><input type="checkbox" name="check_sub_items[]" id="item_' . $item_num . '" class="check_sub_items" data-id="' . $p->id . '"></td>';
      $html .= '<td>' . $p->product_name . '';
      if (!empty($p->variation_enable)) {
        $variations = ProductVariation::where('product_id', $p->id)->where('status', '1')->get();

        if (!$variations->isEmpty()) {
          $html .= '<div class="row">';
          foreach ($variations as $key => $variation) {
            $html .= '<div class="col-lg-12">';

            $variation_price_check = !empty($variation->variation_special_price) ? $variation->variation_special_price : $variation->variation_price;
            if (!empty($variation_price_check)) {
              $price = GlobalController::get_product_pricing(md5($p->id), "", $variation->id);

              $html .= '<br><label><input type="checkbox" name="check_product_variation[]"  class="check_product_variation" data-row="' . $variation->product_id . '" data-id="' . $variation->id . '"> &nbsp;' . $variation->variation_name . '</label>';
              $html .= '<br>&nbsp; ' . $translation_data['backendlang']['backendlang']['Available_Stock'] ?? 'Available Stock' . ': ' . GlobalController::variation_balance_quantity($variation->id) . '';
              $html .= '<br>&nbsp; RM ' . number_format($price['product_price'], 2) . '';
            } else {
              $html .= '<br><label>' . ($translation_data['backendlang']['backendlang']['Option'] ?? 'Option') . ': ' . $variation->variation_name . '</label>';
              $html .= '<br><label><input type="checkbox" name="check_product_variation[]" class="check_product_variation" data-id="' . $variation->id . '" hidden></label>';
            }
            $second_variations = ProductSecondVariation::where('variation_id', $variation->id)->where('status', '1')->get();
            if (!$second_variations->isEmpty()) {
              foreach ($second_variations as $sv) {
                $price = GlobalController::get_product_pricing(md5($p->id), "", $variation->id, $sv->id);

                $html .= '<br><label><input type="checkbox" name="second_variation_id[]" class="second_variation_id"  data-row="' . $sv->product_id . '"   id="' . $sv->variation_id . '" data-id="' . $sv->id . '"> &nbsp;' . $sv->variation_name . '</label>';
                $html .= '<br>&nbsp; ' . ($translation_data['backendlang']['backendlang']['Available_Stock'] ?? 'Available Stock') . ': ' . GlobalController::second_variation_balance_quantity($sv->id) . '';
                $html .= '<br>&nbsp; RM ' . number_format($price['product_price'], 2) . '';
              }
            }
            $html .= '</div>';
          }
        }
        $html .= '</div>';
      } else {
        $price = GlobalController::get_product_pricing(md5($p->id));

        $html .= '<br>&nbsp; ' . ($translation_data['backendlang']['backendlang']['Available_Stock'] ?? 'Available Stock') . ': ' . GlobalController::balance_quantity($p->id) . '';
        $html .= '<br>&nbsp; RM ' . number_format($price['product_price'], 2) . '';
      }
      $html .= '</td>';
      $html .= '<tr>';
    }
    return $html;
  }

  public function save_flash_product(Request $request)
  {
    DB::beginTransaction();

    try {

      $flash_sale_id = !empty($request->flash_sale_id) ? $request->flash_sale_id : '0';

      if($flash_sale_id == 0){
        $flash_sales = FlashSale::where('status',4)->first();
        
        if(empty($flash_sales)){
          $flash_sales = new FlashSale();
        }
   
        $flash_sales->title = $request->title;

        if($request->start){
          $flash_sales->start = date('Y-m-d H:i:s', strtotime($request->start));
        }
        
        if($request->end){
          $flash_sales->end = date('Y-m-d H:i:s', strtotime($request->end));
        }

        $flash_sales->status = 4;

        $flash_sales->save();

        $flash_sale_id = $flash_sales->id;
      }

      if (!empty($request->product)) {
        $product = Product::whereIn('id', explode(',', $request->product))->where('status', '1')->get();
        foreach ($product as $key => $p) {
          if (empty($p->variation_enable)) {
            $getQtyBalance = GlobalController::balance_quantity($p->id);
            if (!empty($getQtyBalance) && $getQtyBalance > 0) {
              $FlashItem = new FlashSaleProductDetail;
              $FlashItem->flash_sale_id = $flash_sale_id;
              $FlashItem->product_id = $p->id;
              $FlashItem->status = '1';

              $FlashItem->save();

              $insert_flash_sale_product_prices = new FlashSaleProductPrice();
              $insert_flash_sale_product_prices->flash_sale_product_detail_id = $FlashItem->id;
              $insert_flash_sale_product_prices->product_id = $FlashItem->product_id;
              $insert_flash_sale_product_prices->variation_id = $FlashItem->variation_id;
              $insert_flash_sale_product_prices->second_variation_id = $FlashItem->second_variation_id;

              $insert_flash_sale_product_prices->save();

              $agent_levels = AgentLevel::where('status', '1')
                ->get();

              foreach ($agent_levels as $key => $agent_level) {
                $insert_flash_sale_product_prices = new FlashSaleProductPrice();
                $insert_flash_sale_product_prices->flash_sale_product_detail_id = $FlashItem->id;
                $insert_flash_sale_product_prices->product_id = $FlashItem->product_id;
                $insert_flash_sale_product_prices->variation_id = $FlashItem->variation_id;
                $insert_flash_sale_product_prices->second_variation_id = $FlashItem->second_variation_id;
                $insert_flash_sale_product_prices->agent_lvl_id = $agent_level->id;

                $insert_flash_sale_product_prices->save();
              }
            } else {
              return array('status' => '97', 'msg' => 'Quantity stock of ' . $p->product_name . ' not enough.');
            }
          }
        }
      }

      if (!empty($request->item_variation)) {
        $variation = ProductVariation::select('p.*', 'product_variations.*', 'p.id as pid', 'product_variations.id as vid')
          ->leftjoin('products as p', 'product_variations.product_id', 'p.id')
          ->whereIn('product_variations.id', explode(',', $request->item_variation))
          ->get();

        foreach ($variation as $key => $variations) {
          $getQtyBalance = GlobalController::variation_balance_quantity($variations->vid);
          if (!empty($getQtyBalance) && $getQtyBalance > 0) {
            $FlashItem = new FlashSaleProductDetail;
            $FlashItem->flash_sale_id = $flash_sale_id;
            $FlashItem->product_id = $variations->pid;
            $FlashItem->variation_id = $variations->vid;
            $FlashItem->status = '1';

            $FlashItem->save();

            $insert_flash_sale_product_prices = new FlashSaleProductPrice();
            $insert_flash_sale_product_prices->flash_sale_product_detail_id = $FlashItem->id;
            $insert_flash_sale_product_prices->product_id = $FlashItem->product_id;
            $insert_flash_sale_product_prices->variation_id = $FlashItem->variation_id;
            $insert_flash_sale_product_prices->second_variation_id = $FlashItem->second_variation_id;

            $insert_flash_sale_product_prices->save();

            $agent_levels = AgentLevel::where('status', '1')
              ->get();

            foreach ($agent_levels as $key => $agent_level) {
              $insert_flash_sale_product_prices = new FlashSaleProductPrice();
              $insert_flash_sale_product_prices->flash_sale_product_detail_id = $FlashItem->id;
              $insert_flash_sale_product_prices->product_id = $FlashItem->product_id;
              $insert_flash_sale_product_prices->variation_id = $FlashItem->variation_id;
              $insert_flash_sale_product_prices->second_variation_id = $FlashItem->second_variation_id;
              $insert_flash_sale_product_prices->agent_lvl_id = $agent_level->id;

              $insert_flash_sale_product_prices->save();
            }
          } else {
            return array('status' => '97', 'msg' => 'Quantity stock of ' . $variation->product_name . ', Variation: ' . $variation->variation_name . ' not enough.');
          }
        }
      }

      if (!empty($request->second_variation)) {
        $sec_variation = ProductSecondVariation::select('p.*', 'pv.*', 'product_second_variations.*', 'p.id as pid', 'pv.id as pvid', 'pv.variation_name as pvName')
          ->leftjoin('product_variations as pv', 'product_second_variations.variation_id', 'pv.id')
          ->leftjoin('products as p', 'product_second_variations.product_id', 'p.id')
          ->whereIn('product_second_variations.id', explode(',', $request->second_variation))
          ->get();

        foreach ($sec_variation as $sv) {
          $getQtyBalance = GlobalController::second_variation_balance_quantity($sv->id);
          if (!empty($getQtyBalance) && $getQtyBalance > 0) {

            $FlashItem = new FlashSaleProductDetail;
            $FlashItem->flash_sale_id = $flash_sale_id;
            $FlashItem->product_id = $sv->product_id;
            $FlashItem->variation_id = $sv->pvid;
            $FlashItem->second_variation_id = $sv->id;
            $FlashItem->status = '1';

            $FlashItem->save();

            $insert_flash_sale_product_prices = new FlashSaleProductPrice();
            $insert_flash_sale_product_prices->flash_sale_product_detail_id = $FlashItem->id;
            $insert_flash_sale_product_prices->product_id = $FlashItem->product_id;
            $insert_flash_sale_product_prices->variation_id = $FlashItem->variation_id;
            $insert_flash_sale_product_prices->second_variation_id = $FlashItem->second_variation_id;

            $insert_flash_sale_product_prices->save();

            $agent_levels = AgentLevel::where('status', '1')
              ->get();

            foreach ($agent_levels as $key => $agent_level) {
              $insert_flash_sale_product_prices = new FlashSaleProductPrice();
              $insert_flash_sale_product_prices->flash_sale_product_detail_id = $FlashItem->id;
              $insert_flash_sale_product_prices->product_id = $FlashItem->product_id;
              $insert_flash_sale_product_prices->variation_id = $FlashItem->variation_id;
              $insert_flash_sale_product_prices->second_variation_id = $FlashItem->second_variation_id;
              $insert_flash_sale_product_prices->agent_lvl_id = $agent_level->id;

              $insert_flash_sale_product_prices->save();
            }
          } else {
            return array('status' => '97', 'msg' => 'Quantity stock of ' . $sv->product_name . ', Variation: ' . $sv->pvName . ', Option: ' . $sv->variation_name . ' not enough.');
          }
        }
      }


      DB::commit();


      return array('status' => '1', 'flash_sale_id' => !empty($request->flash_sale_id) ? $request->flash_sale_id : '');
    } catch (Throwable $e) {
      DB::rollback();
      $message = $e->getMessage();
      return $message;
    }
  }

  public function display_flash_products(Request $request)
  {

    $item = FlashSaleProductDetail::select('flash_sale_product_details.*', 'p.*', 'p.id as pid')
      ->leftjoin('products as p', 'flash_sale_product_details.product_id', 'p.id');

    if (!empty($request->get('flash_sale_id'))) {
      $item->where('flash_sale_id', $request->get('flash_sale_id'));
    } else {
      $item->where('flash_sale_id', '0');
    }

    $item->where('flash_sale_product_details.status', '1')
      ->get();


    $count = $item->count();
    $discount_count = 0;
    $purchase_count = 0;
    $price_count = 0;
    $hidden_price_count = 0;
    $html = '';
    $html .= '<div class="container-box" style="margin-top:20px;">';
    $html .= '<small>Total (' . $count . ') Products</small>';
    $html .= '<table class="table">';
    $html .= '<tr>
                    <th><input type="checkbox" name="check_all_sub_items" class="check_all_sub_items"></th>
                    <th>Product</th>
                    <th></th>
                    <th>Current Price</th>
                    <th>Add-on Price</th>
                    <th>Add-on Discount</th>
                    <th>Stock</th>
                    <th>Purchase Limit</th>
                    <th>Action</th>
                  </tr>';
    $html .= '<tbody class="display_items">';
    foreach ($item as $items) {
      $html .= '<tr>';
      $html .= '<td><input type="checkbox" name="sub_item_check" class="sub_item_check" data-id="' . $items->id . '"></td>';
      $html .= '<td>';
      $html .= '<img src="' . asset(!empty($items->get_product_det->first_image->image) ? $items->get_product_det->first_image->image : '') . '" width="80" height="80">&nbsp;';
      $html .= '</td>';
      $html .= '<td>';
      $html .= '<p>' . $items->product_name . '</p>';
      if (!empty($items->variation_id)) {
        $variations = ProductVariation::where('id', $items->variation_id)->first();
        if (!empty($variations->id)) {
          $html .= '<br><p>Variation: ' . $variations->variation_name . '</p>';
          if (!empty($items->second_variation_id)) {
            $sec_variation = ProductSecondVariation::where('id', $items->second_variation_id)->first();
            if (!empty($sec_variation->id)) {
              $html .= '<br><p>Option: ' . $sec_variation->variation_name . '</p>';
            }
          }
        }
        // $html .= '123';
      }
      $html .= '</td>';
      if (!empty($items->variation_id)) {
        $variations = ProductVariation::where('id', $items->variation_id)->first();
        if (!empty($variations->id)) {
          $price = !empty($variations->variation_special_price) ? $variations->variation_special_price : $variations->variation_price;
          if (!empty($price)) {
            $price = $price;
          }
        } else {
          $price = !empty($items->special_price) ? $items->special_price : $items->price;
        }

        if (!empty($items->second_variation_id)) {
          $sec_variation = ProductSecondVariation::where('id', $items->second_variation_id)->where('variation_id', $items->variation_id)->first();

          if (!empty($sec_variation->id)) {
            $price = !empty($sec_variation->variation_special_price) ? $sec_variation->variation_special_price : $sec_variation->variation_price;
          }
        }
      } else {
        $price = !empty($items->special_price) ? $items->special_price : $items->price;
      }

      $html .= '<td>RM ' . number_format(!empty($price) ? $price : '0', 2) . '</td>';
      $html .= '<input type="hidden" name="hidden_price_count" value="' . number_format(!empty($price) ? $price : '0', 2) . '" id="hidden_price_' . $hidden_price_count++ . '">';
      if (!empty($items->add_on_discount)) {
        $add_on_price = $price - ($price * ($items->add_on_discount / 100));
        $html .= '<td><input type="text" name="add_on_price[]" value="' . $add_on_price . '" id="add_on_price_' . $price_count++ . '" class="form-control form-control-sm" onkeypress="isNumberKey(event)"></td>';
      } else {
        $html .= '<td><input type="text" name="add_on_price[]" id="add_on_price_' . $price_count++ . '" class="form-control form-control-sm" onkeypress="isNumberKey(event)"></td>';
      }




      if (!empty($items->add_on_discount)) {
        $html .= '<td><input type="text" name="add_on_discount[]" value="' . $items->add_on_discount . '"  id="add_on_discount_' . $discount_count++ . '"  class="form-control form-control-sm add_on_discount" onkeypress="isNumberKey(event)"></td>';
      } else {
        $html .= '<td><input type="text" name="add_on_discount[]"  id="add_on_discount_' . $discount_count++ . '"  class="form-control form-control-sm add_on_discount" onkeypress="isNumberKey(event)"></td>';
      }
      if (!empty($items->variation_id)) {
        $html .= '<td>' . GlobalController::variation_balance_quantity($items->variation_id) . '</td>';
      } else {
        $html .= '<td>' . GlobalController::balance_quantity($items->product_id) . '</td>';
      }

      if (!empty($items->purchase_limit)) {
        $html .= '<td><input type="text" name="purchase_limit" value="' . $items->purchase_limit . '" id="purchase_limits_' . $purchase_count++ . '"  class="form-control form-control-sm" onkeypress="isNumberKey(event)"></td>';
      } else {
        $html .= '<td><input type="text" name="purchase_limit" id="purchase_limits_' . $purchase_count++ . '"  class="form-control form-control-sm" onkeypress="isNumberKey(event)"></td>';
      }

      $html .= '<td><a href="#" class="remove_sub_item" data-id="' . $items->id . '"><i class="bi bi-trash"></i></a></td>';
      $html .= '</tr>';
    }
    $html .= '</tbody>';
    $html .= '</div>';
    return $html;
  }

  public function update_all_flash_sale_product(Request $request)
  {
    try {
      \DB::beginTransaction();

      if (!empty($request->flash_sale_id)) {

        $current_flash_sale = FlashSale::find($request->flash_sale_id);

        $all_details = FlashSaleProductDetail::where('flash_sale_id', $current_flash_sale->id)->where('status', '1')->get();

        if (!empty($request->discount)) {
          foreach ($all_details as $detail) {
            foreach ($detail->get_prices as $flash_price) {
              $original_price = GlobalController::get_product_pricing(md5($flash_price->product_id), "", $flash_price->variation_id, $flash_price->second_variation_id, "", $flash_price->agent_lvl_id);

              $original_price = $original_price['product_price'];

              $discount = $original_price * $request->discount;
              $discount = $discount / 100;

              $input_price = $original_price - $discount;

              $flash_price->price = $input_price;
              $flash_price->save();
            }
          }

          // $add_on_discount = FlashSaleProductPrice::where('status','1')->where('add_on_id', $current_flash_sale->id)->update(['add_on_discount'=> $request->discount]);
        }

        if (!empty($request->price)) {
          foreach ($all_details as $detail) {
            foreach ($detail->get_prices as $flash_price) {
              $flash_price->price = $request->price;
              $flash_price->save();
            }
          }
          // $purchase_limit = FlashSaleProductPrice::where('status','1')->where('add_on_id', $current_flash_sale->id)->update(['purchase_limit'=> $request->limit]);
        }

        $batch_select = FlashSaleProductDetail::where('flash_sale_id', $current_flash_sale->id)->get();
      } else {
        $all_details = FlashSaleProductDetail::where('flash_sale_id', '0')->where('status', '1')->get();

        if (!empty($request->discount)) {
          foreach ($all_details as $detail) {
            foreach ($detail->get_prices as $flash_price) {
              $original_price = GlobalController::get_product_pricing(md5($flash_price->product_id), "", $flash_price->variation_id, $flash_price->second_variation_id, "", $flash_price->agent_lvl_id);

              $original_price = $original_price['product_price'];

              $discount = $original_price * $request->discount;
              $discount = $discount / 100;

              $input_price = $original_price - $discount;

              $flash_price->price = $input_price;
              $flash_price->save();
            }
          }

          // $add_on_discount = FlashSaleProductPrice::where('status','99')->update(['add_on_discount'=> $request->discount]);
        }

        if (!empty($request->price)) {
          foreach ($all_details as $detail) {
            foreach ($detail->get_prices as $flash_price) {
              $flash_price->price = $request->price;
              $flash_price->save();
            }
          }

          // $purchase_limit = FlashSaleProductPrice::where('status','99')->update(['purchase_limit'=> $request->limit]);
        }

        $batch_select = FlashSaleProductDetail::where('flash_sale_id', '0')->get();
      }

      \DB::commit();


      $batch_count = count($batch_select);
      $batch = [];
      foreach ($batch_select as $key => $batch) {
        $batch = [$batch->add_on_discount, $batch->purchase_limit];
      }

      return array('status' => '1');
    } catch (Throwable $e) {
      $message = $e->getMessage();
      return $message;
    }
  }

  public function update_selected_flash_sale_product(Request $request)
  {
    try {
      \DB::beginTransaction();

      if (!empty($request->product)) {
        $flash_prices = FlashSaleProductPrice::whereIn('id', explode(',', $request->product))->get();

        if (!empty($request->discount)) {
          // foreach($all_details as $detail){
          foreach ($flash_prices as $flash_price) {
            $original_price = GlobalController::get_product_pricing(md5($flash_price->product_id), "", $flash_price->variation_id, $flash_price->second_variation_id, "", $flash_price->agent_lvl_id);

            $original_price = $original_price['product_price'];

            $discount = $original_price * $request->discount;
            $discount = $discount / 100;

            $input_price = $original_price - $discount;

            $flash_price->price = $input_price;
            $flash_price->save();
          }
          // }

          // $update_selected = FlashSaleProductPrice::whereIn('id',explode(',', $request->product))->update(['add_on_discount'=>$request->discount]);
        }

        if (!empty($request->price)) {
          foreach ($flash_prices as $flash_price) {
            $flash_price->price = $request->price;
            $flash_price->save();
          }

          // $update_selected_limit = FlashSaleProductPrice::whereIn('id',explode(',', $request->product))->update(['purchase_limit'=>$request->limit]);

        }
        \DB::commit();
        return 1;
      }
    } catch (Throwable $e) {
      \DB::rollback();
      return $e->getMessage();
    }
  }

  public function update_flash_product_details(Request $request)
  {
    try {
      \DB::beginTransaction();

      $flash_sale_product_detail = FlashSaleProductDetail::find($request->id);

      $flash_sale_product_detail->qty = $request->qty;
      $flash_sale_product_detail->save();

      \DB::commit();
      return 1;
    } catch (\Exception $e) {
      \DB::rollback();
      return $e->getMessage();
    }
  }


  public function change_flash_sale_product_status(Request $request)
  {
    try {
      \DB::beginTransaction();

      $flash_sale_product_detail = FlashSaleProductDetail::find($request->flash_sale_product_id);
      if ($request->status == '3') {
        $flash_sale_product_detail->delete();
      } else {
        $flash_sale_product_detail->status = $request->status;
        $flash_sale_product_detail->save();
      }


      $flash_sale_product_price = FlashSaleProductPrice::where('flash_sale_product_detail_id', $request->flash_sale_product_id)
        ->get();

      foreach ($flash_sale_product_price as $price) {
        if ($request->status == '3') {
          $price->delete();
        } else {
          $price->status = $request->status;
          $price->save();
        }
      }

      \DB::commit();
    } catch (\Exception $e) {
      \DB::rollback();
      return $e->getMessage();
    }

    return "ok";
  }

  public function get_cart_link_variation(Request $request)
  {
    $result = NULL;
    $current_product = Product::find($request->product_id);

    if (!empty($current_product->id) && $current_product->variation_enable == '1') {
      $product_variations = $current_product->get_variations;

      if (!$product_variations->isEmpty()) {
        $result = $product_variations;
      }
    }

    return $result;
  }

  public function get_cart_link_second_variation(Request $request)
  {
    $result = NULL;
    $current_product = Product::find($request->product_id);

    if (!empty($current_product->id) && $current_product->variation_enable == '1') {
      $product_variation = ProductVariation::find($request->variation_id);

      if (!empty($product_variation->id) && $current_product->second_variation_enable == '1') {
        $product_second_variations = $product_variation->get_second_variations;

        if (!$product_second_variations->isEmpty()) {
          $result = $product_second_variations;
        }
      }
    }

    return $result;
  }

  public function get_cart_link_product_price(Request $request)
  {
    $product_id = $request->product_id;
    $variation_id = $request->variation_id;
    $second_variation_id = $request->second_variation_id;

    if ($request->product_id == 'undefined') {
      $product_id = NULL;
    }
    if ($request->variation_id == 'undefined') {
      $variation_id = NULL;
    }
    if ($request->second_variation_id == 'undefined') {
      $second_variation_id = NULL;
    }

    $product_pricing = GlobalController::get_product_pricing(md5($product_id), "", $variation_id, $second_variation_id);

    return $product_pricing['product_price'] * $request->qty;
  }

  public function get_merchant_expired_date(Request $request)
  {
    return GlobalController::get_merchant_expired_date($request->merchant, $request->period);
  }

  public function delete_packages(Request $request)
  {
    try {
      \DB::beginTransaction();

      $delete_packages = PackageItem::find($request->id);
      if (!empty($delete_packages->id)) {
        $delete_packages->delete();
      }

      \DB::commit();
    } catch (\Exception $e) {
      \DB::rollback();
      return $e->getMessage();
    } catch (\Error $e) {
      return $e->getMessage();
    }

    return "ok";
  }

  public function ChooseCategory(Request $request)
  {
    $subCategory = SubCategory::select('sub_categories.sub_category_name AS content_name', 'sub_categories.id AS sc_id')
      ->where('sub_categories.category_id', $request->id)
      ->where('sub_categories.status', '1');
    if (Auth::guard('merchant')->check()) {
      $subCategory = $subCategory->where('sub_categories.merchant_id', Auth::user()->code);
    }
    $subCategory = $subCategory->get();

    $leftJoin = DB::raw("(SELECT * FROM product_images ORDER BY created_at ASC) AS i");

    $products = Product::select('products.product_name AS content_name', 'products.id AS p_id', 'i.image', 'special_price', 'price')
      ->leftJoin($leftJoin, function ($join) {
        $join->on('products.id', '=', 'i.product_id');
      })
      ->where('products.category_id', $request->id)
      ->where('products.status', '1')
      ->whereNull('mall')
      ->groupBy('products.id');
    if (Auth::guard('merchant')->check()) {
      $products = $products->where('products.merchant_id', Auth::user()->code);
    }
    $products = $products->get();


    $subCategory = $subCategory->concat($products);

    $subCategory = array_reverse(Arr::sort($subCategory, function ($value) {
      return $value['sc_id'];
    }));

    $categories = Category::where('status', '1');
    if (Auth::guard('merchant')->check()) {
      $categories = $categories->where('categories.merchant_id', Auth::user()->code);
    }
    $categories = $categories->get();

    $top_category = '<div class="ps-shoe__variants">
                          <div class="ps-shoe__variant normal">';
    foreach ($categories as $category) {
      $active = ($category->id == $request->id) ? 'active' : '';
      $top_category .= '<a href="#" class="items_option category_list ' . $active . '" data-filter="' . $category->id . '">
                              ' . $category->category_name . '
                            </a>';
    }

    $top_category .= '</div>
                      </div>';

    $result = "";

    foreach ($subCategory as $sc) {
      $get_product_pricing = GlobalController::get_product_pricing(md5($sc->p_id), $request->selected_buyer);
      $image = !empty($sc->image) ? $sc->image : 'images/no-image-available-icon-6.jpg';
      $link_class = !empty($sc->p_id) ? 'product_items_option' : 'sub_items_option';
      if (!empty($sc->p_id)) {
        $price = !empty($sc->special_price) ? 'RM ' . number_format($sc->special_price, 2) : 'RM ' . number_format($sc->price, 2);
      } else {
        $price = "";
      }

      $result .= '<div class="col-3">
                            <div class="form-group">
                                <a href="#" class="' . $link_class . '" data-filter="' . $sc->sc_id . '" data-id="' . $sc->p_id . '">
                                    <div class="items-list" style="background-image: url(' . GlobalController::get_production_url($image) . ');">
                                        <div class="items-content">
                                            <div class="product_name">
                                                <span>' . $sc->content_name . '</span><br>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>          
                        </div>';
    }

    return array($result, $top_category);
  }

  public function ChooseSubCategory(Request $request)
  {
      $leftJoin = DB::raw("(SELECT * FROM product_images ORDER BY created_at ASC) AS i");

      $products = Product::select('products.product_name AS content_name', 'products.id AS p_id', 'i.image', 'special_price', 'price')
        ->leftJoin($leftJoin, function ($join) {
          $join->on('products.id', '=', 'i.product_id');
        })
        ->where('products.sub_category_id', $request->scid)
        ->where('products.status', '1')
        ->whereNull('mall')
        ->groupBy('products.id');
      if (Auth::guard('merchant')->check()) {
        $products = $products->where('products.merchant_id', Auth::user()->code);
      }
      $products = $products->get();

      $top_category = '';

      $result = "";

      foreach ($products as $sc) {
        $get_product_pricing = GlobalController::get_product_pricing(md5($sc->p_id), $request->selected_buyer);
        $image = !empty($sc->image) ? $sc->image : 'images/no-image-available-icon-6.jpg';
        $link_class = !empty($sc->p_id) ? 'product_items_option' : 'sub_items_option';
        if (!empty($sc->p_id)) {
          $price = !empty($sc->special_price) ? 'RM ' . number_format($sc->special_price, 2) : 'RM ' . number_format($sc->price, 2);
        } else {
          $price = "";
        }

        $result .= '<div class="col-3">
                              <div class="form-group">
                                  <a href="#" class="' . $link_class . '" data-filter="' . $sc->sc_id . '" data-id="' . $sc->p_id . '">
                                      <div class="items-list" style="background-image: url(' . GlobalController::get_production_url($image) . ');">
                                          <div class="items-content">
                                              <div class="product_name">
                                                  <span>' . $sc->content_name . '</span><br>
                                              </div>
                                          </div>
                                      </div>
                                  </a>
                              </div>          
                          </div>';
      }

      return array($result, $top_category);
  }

  public function GetProductVariation(Request $request)
  {

    $products = Product::select('i.image', 'products.*')
      ->leftJoin('product_images AS i', 'i.product_id', 'products.id')
      ->where('products.id', $request->pid)
      ->first();

    $get_product_pricing = GlobalController::get_product_pricing(md5($products->id), $request->selected_buyer);

    $vts = VariationTitle::where('product_id', $request->pid)->where('status', '1')->get();
    $price = (!empty($products->special_price)) ? $products->special_price : $products->price;
    $image = !empty($products->image) ? $products->image : 'images/no-image-available-icon-61.jpg';
    $result =   '<div class="container">
                        <div class="row">
                            <div class="col-4" style="padding: 10px;">
                                <img src="' . GlobalController::get_production_url($image) . '" width="100px">
                            </div>
                            <div class="col-6" style="padding: 10px;">
                                <h4>' . $products->product_name . '</h4>
                                <br>
                                RM <span style="font-size: 18px;" class="pricing">' . $get_product_pricing["product_price_range"] . '</span>
                                <input type="hidden" class="actual_price" value="' . $get_product_pricing["product_price_range"] . '">
                                <br>
                                <div class="form-group quantity-setting">
                                    <button class="btn btn-primary deduct-qty-button">
                                        <i class="bi bi-dash"></i>
                                    </button>
                                    <input type="text" class="form-control" name="quantity" value="1" onkeypress="return isNumberKey(event)">
                                    <button class="btn btn-primary add-qty-button">
                                        <i class="bi bi-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-2" align="right">
                                <a href="#" class="close-bottom-pop-up" style="font-size: 25px;">
                                    x
                                </a>
                            </div>
                        </div>
                   </div>
                   <hr>
                   <div class="container">
                   <div class="variation_area">
                      <div class="ps-product--detail">
                        <div class="ps-product__style">';
    if ($products->variation_enable == '1') {
      if (!$products->get_variations->isEmpty()) {
        $variation_title = !empty($products->variation_title) ? $products->variation_title : 'Option';
        $second_variation_title = !empty($products->second_variation_title) ? $products->second_variation_title : 'Option';
        $result .= '<div class="ps-product__block ps-product__style v_v">
                                    <h4>' . $variation_title . '</h4>
                                        <ul style="height: 50px; overflow: auto;">';
        foreach ($products->get_variations as $vr) {
          $result .= '<li>
                                        <input type="hidden" class="variation_type" value="1">
                                        <a href="#" class="variation_option" data-variation="' . $products->variation_enable . '" data-second-variation="' . $products->second_variation_enable . '" data-id="' . $vr->id . '">
                                            ' . $vr->variation_name . '
                                        </a>
                                    </li>';
        }
        $result .= '</ul>
                            </div>';

        if ($products->second_variation_enable == '1') {
          $result .= '<div class="ps-product__block ps-product__style v_v_2">
                                    <h4>' . $second_variation_title . '</h4>
                                        <ul style="height: 50px; overflow: auto;" class="second-variation-list">';
          foreach ($products->get_second_variations as $vr_sc) {
            $result .= '<li>
                                        <input type="hidden" class="variation_type" value="1">
                                        <a href="#" class="second_variation_option" data-id="' . $vr_sc->id . '">
                                            ' . $vr_sc->variation_name . '
                                        </a>
                                    </li>';
          }
          $result .= '</ul>
                            </div>';
        }
      }
    }
    $result .= '</div>';
    $data = GlobalController::get_translations();
    $result .= '<div class="container">';
    $result .= '<div class="form-group">
                <h4>'.(isset($data['backendlang']['backendlang']['Remark']) ? $data['backendlang']['backendlang']['Remark'] : 'Remark').'</h4>
                          <hr>
                <textarea class="form-control remark" name="remark" id="remark" placeholder="'.(isset($data['backendlang']['backendlang']['Remark']) ? $data['backendlang']['backendlang']['Remark'] : 'Remark').'"></textarea>
                      </div>';
    $result .= '<div class="form-group">
                            <button class="btn btn-block add-to-cart-button btn-success" data-id="' . $products->id . '">
                  '.(isset($data['backendlang']['backendlang']['Add_To_Cart']) ? $data['backendlang']['backendlang']['Add_To_Cart'] : 'Add To Cart').'
                            </button>
                        </div>
                      </div>
                  </div>
                </div>';
    $result .= '</div>';

    return $result;
  }

  public function ChooseItem(Request $request)
  {
    if ($request->quantity <= 0) {
      return "quantity error";
    }

    $member = !empty($request->member) ? $request->member : '';

    $product = Product::find($request->pid);

    $BalanceQty = GlobalController::balance_quantity($request->pid);

    if ($BalanceQty < $request->quantity) {
      return "quantity exceed error";
    }

    $check = Cart::where('product_id', $request->pid)
      ->where('status', '1')
      ->where('cashier', '1');
    if (isset($request->sub_category_id) && !empty($request->sub_category_id) && isset($request->second_sub_category_id) && !empty($request->second_sub_category_id)) {
      $check = $check->where('sub_category_id', $request->sub_category_id)
        ->where('second_sub_category_id', $request->second_sub_category_id);
    }
    $check = $check->first();

    if (isset($check) && !empty($check->id)) {

      $update = Cart::find($check->id);
      $update2 = Cart::find($check->id);
      $addRemark = $request->remark;
      $totalQty = $update->qty + $request->quantity;
      if ($request->quantity <= $BalanceQty) {
        $update = $update->update(['qty' => $totalQty]);
        $update2 = $update2->update(['remark' => $addRemark]);
      } else {
        return "quantity personal exceed";
      }
    } else {
      $input = $request->all();
      $input['product_id'] = $request->pid;
      $input['sub_category_id'] = $request->sub_category_id;
      $input['second_sub_category_id'] = $request->second_sub_category_id;
      $input['qty'] = $request->quantity;
      $input['cashier'] = $request->cashier;
      $input['remark'] = $request->remark;

      $cart = Cart::create($input);
    }


    $sCart = Cart::where('cashier', '1')
      ->get();

    $result =  "";
    $translation_data = GlobalController::get_translations();
    $remarkLabel = $translation_data['backendlang']['backendlang']['Remark'] ?? 'Remark';
    foreach ($sCart as $cart) {
      $get_pricing[$cart->id] = GlobalController::get_product_pricing(md5($cart->product_id), $member, $cart->sub_category_id, $cart->second_sub_category_id);

      $price = $get_pricing[$cart->id]['product_price'];
      $addons = "";
      if (!empty($cart->get_fv_det->id)) {
        $addons .= '<p>
                                <small>
                                    <i class="bi bi-plus-square-fill" style="color: red;"></i> ' . $cart->get_product_det->variation_title . ' - ' . $cart->get_fv_det->variation_name . '
                                </small>
                            </p>';
      }

      if (!empty($cart->get_sv_det->id)) {
        $addons .= '<p>
                                <small>
                                    <i class="bi bi-plus-square-fill" style="color: red;"></i> ' . $cart->get_product_det->second_variation_title . ' - ' . $cart->get_sv_det->variation_name . '
                                </small>
                            </p>';
      }
      // $addon_price = 0;
      // if(!empty($cart->c_sc_id)){
      //     $exps = explode(',', $cart->c_sc_id);

      //     foreach($exps as $exp){
      //         $items = ProductVariation::find($exp);
      //         $title = VariationTitle::find($items->variation_title_id);
      //         $addons .= '<p>
      //                         <small>
      //                             +'.$title->variation_title.' - '.$items->variation_name.'('.number_format($items->variation_price, 2).')
      //                         </small>
      //                     </p>';
      //         if($items->variation_price_type == '2'){
      //             $addon_price -= $items->variation_price;
      //         }else{
      //             $addon_price += $items->variation_price;
      //         }
      //     }
      // }

      if ($cart->get_product_det->variation_enable == 1) {
        if ($cart->get_product_det->second_variation_enable) {
          $stockBalance = GlobalController::second_variation_balance_quantity($cart->second_sub_category_id);
        } else {
          $stockBalance = GlobalController::variation_balance_quantity($cart->sub_category_id);
        }
      } else {
        $stockBalance = GlobalController::balance_quantity($cart->product_id);
      }
      $everyRemark = $cart->remark;
      $remarkArray = explode(',', $everyRemark);
      $remarkList = $remarkLabel . ': ' . $cart->remark . '<br>';
      // foreach ($remarkArray as $value) {
      //     $remarkList .='Remark: '.$value.'<br>';
      // }

      $result .= '<div class="row got-item">
                            <div class="col-3">
                                <input type="hidden" name="cid" class="cid" value="' . md5($cart->id) . '">
                                ' . $cart->get_product_det->product_name . '
                                ' . $addons . '
                            </div>
                            <div class="col-3" align="right">
                                RM ' . number_format(($price), 2) . '
                            </div>
                            <div class="col-3" align="right">
                                <div class="form-group quantity-setting">
                                    <button class="btn btn-primary deduct-qty-button">
                                        <i class="bi bi-dash"></i>
                                    </button>
                                    <input type="text" class="form-control" name="quantity" value="' . $cart->qty . '" onkeypress="return isNumberKey(event)">
                                    <button class="btn btn-primary add-qty-button">
                                        <i class="bi bi-plus"></i>
                                    </button>
                                    <input type="hidden" name="balance_quantity" value="' . $stockBalance . '">
                                </div>
                            </div>
                            <div class="col-3 " align="right">
                                RM 
                                <span class="row-total-price">
                                    ' . number_format(($price) * $cart->qty, 2) . '
                                </span>
                            </div>
                            <div class="col-md-12" style="word-wrap: break-word;">'
        . $remarkList .
        '<hr>
                            </div>
                        </div>';
    }

    return $result;
  }

  public function CountCashierCart(Request $request)
  {
    $member = !empty($request->members) ? $request->members : '';
    $carts = Cart::where('cashier', '1')
      ->groupBy('carts.id')
      ->get();
    $amount = 0;
    $addon_price = 0;
    $totalQty = 0;
    foreach ($carts as $cart) {
      $get_product_pricing = GlobalController::get_product_pricing(md5($cart->product_id), $member, $cart->sub_category_id, $cart->second_sub_category_id);
      $amount += $get_product_pricing['product_price'] * $cart->qty;

      $totalQty += $cart->qty;
    }

    $totatCart = $totalQty;

    return array($amount, $totatCart);
  }

  public function checkTableAvailable(Request $request)
  {
    $checkAvailable = Transaction::where('table_id', $request->id)->where('status', '99')
      ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), date('Y-m-d'))
      ->orderBy('created_at', 'desc')
      ->first();

    if (!empty($checkAvailable->id)) {
      return $checkAvailable->transaction_no;
    } else {
      return 0;
    }
  }

  public function cashier_pay(Request $request)
  {

    try {
      \DB::beginTransaction();

      if (!empty($request->paid_amount)) {
        $paid_amount = preg_replace("/[^0-9\.]/", '', $request->paid_amount);
      } else {
        $paid_amount = 0;
      }

      $website_setting = GlobalController::website_setting();

      $new_member = [];
      // if(!empty($request->member_phone)){
      //     $member = User::where('phone', $request->member_phone)->first();

      //     if(!empty($member->id)){
      //         $new_member = User::find($member->id);
      //     }else{
      //         $member_display_code = GlobalController::MemberDisplayCode();

      //         $new_member = new User();
      //         $new_member->master_id = "AD000001";
      //         if(Auth::guard('merchant')->check()){
      //         $new_member->dual_master_id = Auth::user()->code;
      //         }
      //         $new_member->code = GlobalController::MemberCode();
      //         $new_member->display_code = $member_display_code[0];
      //         $new_member->display_running_no = $member_display_code[1];
      //         $new_member->phone = $request->member_phone;
      //         $new_member->password = Hash::make($request->member_phone);
      //         $new_member->status = 1;
      //         $new_member->save();

      //         $add_affiliates = GlobalController::add_affiliates($new_member->code, $new_member->master_id);
      //         if($add_affiliates != 'ok'){
      //             throw new \Exception($add_affiliates);
      //         }
      //     }
      // }

      if (!empty($request->member_phone)) {
        $buyer = Agent::where('code', $request->member_phone)->first();
        if (empty($buyer->id)) {
          $buyer = User::where('code', $request->member_phone)->first();
        }

        $new_member = $buyer;
      }

      $get_point_wallet = 0;
      if (!empty($new_member->id)) {
        // $get_point_wallet = GlobalController::get_point_wallet($new_member->code);
      }

      if (!empty($request->order_no)) {
        $transaction = Transaction::where('transaction_no', $request->order_no)->first();
        $grand_total = !empty($request->totalDiscount) ? $transaction->grand_total - $request->totalDiscount : $transaction->grand_total;


        // if($request->used_point == 1 && !empty($new_member->phone)){

        //     $rm_to_point = $website_setting->rm_to_point;
        //     $rm_to_point = !empty($rm_to_point) ? $rm_to_point : 1;

        //     $transaction->used_point = $get_point_wallet;

        //     $grand_total = $grand_total - ($get_point_wallet * $rm_to_point);
        //     $transaction->grand_total = $grand_total;
        //     $transaction->grand_total_point = ($get_point_wallet * $rm_to_point);
        // }

        $reference_number = !empty($request->cc_reference_number) ? $request->cc_reference_number : $request->reference_number;
        $qr_pay_id = "";
        $cc_bank_id = "";
        if ($request->payment_method == '2') {
          $qr_pay_id = $request->qr_type;
          $cc_bank_id = "";
          $paid_amount = $grand_total;
        } elseif ($request->payment_method == '3') {
          $qr_pay_id = "";
          $cc_bank_id = $request->bank_name;
          $paid_amount = $grand_total;
        }

        if ($paid_amount >= $transaction->grand_total) {
          $status = '1';
        } else {
          $status = '99';
        }


        $transaction->user_id = !empty($new_member) ? $new_member->code : null;
        $transaction->payment_method = $request->payment_method;
        $transaction->paid_amount = $paid_amount;
        $transaction->cc_bank_id = $cc_bank_id;
        $transaction->qr_pay_id = $qr_pay_id;
        $transaction->reference_number = $reference_number;
        $transaction->status = $status;
        $transaction->discount = $request->totalDiscount;
        $transaction->discount_type = $request->payment_discount_type;
        $transaction->discount_amount = $request->combine_payment_discount_amount;
        // $this->removeQrSection($request->order_no);

        $transaction->save();
      } else {
        $reference_number = !empty($request->cc_reference_number) ? $request->cc_reference_number : $request->reference_number;
        $qr_pay_id = "";
        $cc_bank_id = "";
        if ($request->payment_method == '2') {
          $qr_pay_id = $request->qr_type;
          $cc_bank_id = "";
          $paid_amount = $request->grand_total;
        } elseif ($request->payment_method == '3') {
          $qr_pay_id = "";
          $cc_bank_id = $request->bank_name;
          $paid_amount = $request->grand_total;
        } else {
          $qr_pay_id = '0';
          $cc_bank_id = '0';
        }

        $leftJoin = DB::raw("(SELECT * FROM product_images ORDER BY created_at ASC) AS i");
        $carts = Cart::where('cashier', '1')
          ->get();

        $totalAmount = 0;
        $totalAddOn = 0;
        foreach ($carts as $cart) {
          $get_product_pricing = GlobalController::get_product_pricing(md5($cart->product_id), $request->member_phone, $cart->sub_category_id, $cart->second_sub_category_id);
          $totalAmount += ($get_product_pricing['product_price'] * $cart->quantity);
        }
        $subtotal = $totalAmount;

        $transaction = new Transaction();

        $grand_total = $request->grand_total;

        if ($request->used_point == 1 && !empty($new_member->phone)) {
          $rm_to_point = $website_setting->rm_to_point;
          $rm_to_point = !empty($rm_to_point) ? $rm_to_point : 1;

          $transaction->used_point = $get_point_wallet;

          // $grand_total = ($get_point_wallet * $rm_to_point);
        }

        $transaction->user_id = !empty($new_member) ? $new_member->code : null;
        $transaction->transaction_no = GlobalController::GenerateTransactionNo();
        $transaction->payment_method = $request->payment_method;
        $transaction->paid_amount = $paid_amount;
        $transaction->order_type = $request->order_type;
        $transaction->shipping_fee = 0;
        $transaction->discount = $request->totalDiscount;
        $transaction->discount_type = $request->payment_discount_type;
        $transaction->discount_amount = $request->combine_payment_discount_amount;
        $transaction->grand_total = $grand_total;
        // $transaction->table_id = $request->table_id;
        $transaction->cc_bank_id = $cc_bank_id;
        $transaction->sub_total = $subtotal;
        $transaction->qr_pay_id = $qr_pay_id;
        $transaction->reference_number = $reference_number;
        $transaction->save();

        foreach ($carts as $cart) {


          $get_product_pricing[$cart->id] = GlobalController::get_product_pricing(md5($cart->product_id), $request->member_phone, $cart->sub_category_id, $cart->second_sub_category_id);
          $actual_price = $get_product_pricing[$cart->id]['product_price'];

          $get_point = 0;
          if (!empty($cart->get_point)) {
            if ($cart->get_point_type == 'Percentage') {
              $get_point = $actual_price * $cart->get_point / 100;
            } else {
              $get_point = $cart->get_point;
            }
          }

          $transaction_detail = new TransactionDetail();

          $authorise_merchant = !empty($_COOKIE['vmerchant']) ? $_COOKIE['vmerchant'] : '';
          $get_authorise_status = GlobalController::check_autorize_status($authorise_merchant);
          if ($get_authorise_status['status'] == 1) {
            $transaction_detail->merchant_id = !empty($get_authorise_status['result']['code']) ? $get_authorise_status['result']['code'] : '';
          }
          $transaction_detail->transaction_id = $transaction->id;
          $transaction_detail->product_id = $cart->product_id;
          $transaction_detail->item_code = $cart->get_product_det->item_code;
          $transaction_detail->product_code = $cart->get_product_det->product_code;
          $transaction_detail->product_image = !empty($cart->get_product_det->first_image->image) ? $cart->get_product_det->first_image->image : '';
          $transaction_detail->product_name = $cart->get_product_det->product_name;
          $transaction_detail->unit_price = $actual_price;
          $transaction_detail->quantity = $cart->qty;
          $transaction_detail->variation_id = $cart->sub_category_id;
          $transaction_detail->second_variation_id = $cart->second_sub_category_id;
          $transaction_detail->remark = $cart->remark;
          $transaction_detail->get_point = $get_point;
          $transaction_detail->status = 1;
          $transaction_detail->save();
        }

        $isMember = User::where('code', $transaction->user_id)->first();
        $isAgent = Agent::where('code', $transaction->user_id)->first();
        $get_merchant_register = Agent::where('register_transaction', $transaction->transaction_no)->first();

        if (!empty($get_merchant_register->id)) {
          $get_merchant_register->status = 1;
          $get_merchant_register->verify_status = 1;
          $get_merchant_register->save();

          $add_affiliates = GlobalController::add_affiliates($get_merchant_register->code, $get_merchant_register->master_id);
          if ($add_affiliates != 'ok') {
            throw new \Exception($add_affiliates);
            // $Generate_Refferal_Reward = $this->Generate_Refferal_Reward($get_merchant->master_id);
          }
        }

        $transaction_voucher_assign = GlobalController::transaction_voucher_assign($transaction->id);
        if ($transaction_voucher_assign != 'ok') {
          throw new \Exception($transaction_voucher_assign);
        }

        if (
          !empty($isMember->id) ||
          !empty($isAgent->id)
        ) {
          $upgrade_agent_with_package = GlobalController::upgrade_agent_with_package($transaction->transaction_no);
          if ($upgrade_agent_with_package != 'ok') {
            throw new \Exception($upgrade_agent_with_package);
          }
        }
        if (empty($transaction->commission_disabled)) {
          $rebate_commission = GlobalController::rebate_commission($transaction->user_id, $transaction->transaction_no);
          if ($rebate_commission != 'ok') {
            throw new \Exception($rebate_commission);
          }

          $heirarchy_commission = GlobalController::heirarchy_commission($transaction->user_id, $transaction->transaction_no);
          if ($heirarchy_commission != 'ok') {
            throw new \Exception($heirarchy_commission);
          }
        }

        $purchase_from_customer_deduct_stock_commission = GlobalController::purchase_from_customer_deduct_stock_commission($transaction->transaction_no);
        if ($purchase_from_customer_deduct_stock_commission != 'ok') {
          throw new \Exception($purchase_from_customer_deduct_stock_commission);
        }

        // $add_on = [];
        // $select_ds = TransactionDetail::where('transaction_id', $transaction->id)->get();

        // foreach($select_ds as $d){
        //     if(!empty($d->variation_id)){
        //         $exps = explode(',', $d->variation_id);
        //         foreach($exps as $exp){
        //             $ado_d = ProductVariation::find($exp);
        //             $ado_t = VariationTitle::find($ado_d->variation_title_id);
        //             $add_on_detail = new TransactionAddon();
        //             $add_on_detail->details_id = $d->id;
        //             $add_on_detail->add_on_type = $ado_t->variation_type_two;
        //             $add_on_detail->transaction_id = $transaction->id;
        //             $add_on_detail->add_on_id = $ado_d->id;
        //             $add_on_detail->add_on_title = $ado_t->variation_title;
        //             $add_on_detail->add_on_name = $ado_d->variation_name;
        //             $add_on_detail->price_type = $ado_d->variation_price_type;
        //             $add_on_detail->price = $ado_d->variation_price;
        //             $add_on_detail->qty = $d->quantity;
        //             $add_on_detail->status = '99';
        //             $add_on_detail->save();
        //         }
        //     }
        // }

        $delete_cart = Cart::where('cashier', '1')->delete();
      }

      \DB::commit();

      return "ok";
    } catch (\Exception $e) {
      \DB::rollback();
      return $e->getMessage() . ' - ' . $e->getLine();
    } catch (\Error $e) {
      \DB::rollback();
      return $e->getMessage() . ' - ' . $e->getLine();
    }
  }

  public function removeQrSection($transaction_no)
  {
    $transaction = Transaction::where('transaction_no', $transaction_no)->first();


    if (!empty($transaction->id) && !empty($transaction->qr_id)) {
      $count = Transaction::where('status', '99')->where('qr_id', $transaction->qr_id)->get();

      //All Paid
      if (count($count) == 0) {
        $qrid = QrCode::find($transaction->qr_id)->delete();
      }
    }
  }

  public function GetTableHistory(Request $request)
  {
    if (!empty($request->search_date)) {
      $searchDate = date('Y-m-d', strtotime($request->search_date));
    } else {
      $searchDate = date('Y-m-d');
    }

    $histories = Transaction::where('table_id', $request->id)
      ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), $searchDate)
      ->orderBy('created_at', 'desc')
      ->get();

    $result = "<div class='' align='center'>
                      <h4>Table Order History</h4>
                      <hr>
                   </div>
                   Filter Date: <input type='text' class='form-control search_date' name='search_date'>
                   <hr>
                   <div class='result-box'>";
    foreach ($histories as $history) {

      $totalGrand = $this->GetTransactionGrandTotal($history->id);
      if (!empty($history->combined_id)) {
        $totalGrand = $totalGrand - $history->discount;
      }

      if ($history->status == '1') {
        $payment_status = '<label class="badge bg-success">Paid</label>';
      } elseif ($history->status == '95') {
        $payment_status = '<label class="badge bg-danger">Cancelled</label>';
      } else {
        $payment_status = '<label class="badge bg-danger">Unpaid</label>';
      }

      $result .= '<div class="form-group container-box">
                        <div class="history-header">
                          <div class="row">
                            <div class="col-6">
                              ' . $history->transaction_no . '
                              <br>
                              ' . $payment_status . '
                            </div>
                            <div class="col-6" align="right">
                              Total: <h4>RM ' . number_format($totalGrand, 2) . '</h4>
                            </div>
                          </div>
                        </div>
                        <hr>';

      if (!empty($history->combined_id)) {
        $expTran = explode(', ', $history->combined_id);
        $ts = Transaction::whereIn('transaction_no', $expTran)->get();
        $ids = [];
        foreach ($ts as $t) {
          $ids[] = $t->id;
        }

        $detail1 = TransactionDetail::where('transaction_id', $history->id)->get();
        $detail2 = TransactionDetail::whereIn('transaction_id', $ids)->get();

        $history_details = $detail1->concat($detail2);
      } else {
        $history_details = TransactionDetail::where('transaction_id', $history->id)->get();
      }

      foreach ($history_details as $history_detail) {
        $history_add_ons = TransactionAddon::where('details_id', $history_detail->id)->get();
        $result_aod = "";
        $add_on_price = 0;
        if ($history_detail->status == '99') {
          $cook_status = '<label class="badge bg-danger">Not Ready</label>';
        } elseif ($history_detail->status == '1') {
          $cook_status = '<label class="badge bg-info">Preparing</label>';
        } else {
          $cook_status = '<label class="badge bg-success">Ready</label>';
        }

        if (!empty($history_detail->remark)) {
          $remark = $history_detail->remark;
        } else {
          $remark = "Empty(No Remark)";
        }

        foreach ($history_add_ons as $history_add_on) {
          if ($history_add_on->price_type == '2') {
            $result_aod .= '<p>
                                    <small>
                                       - ' . $history_add_on->add_on_title . ' - ' . $history_add_on->add_on_name . ' (' . number_format($history_add_on->price, 2) . ')
                                    </small>
                                  </p>';
          } else {
            $result_aod .= '<p>
                                    <small>
                                       + ' . $history_add_on->add_on_title . ' - ' . $history_add_on->add_on_name . ' (' . number_format($history_add_on->price, 2) . ')
                                    </small>
                                  </p>';
          }
          if ($history_add_on->price_type == '2') {
            $add_on_price -= $history_add_on->price;
          } else {
            $add_on_price += $history_add_on->price;
          }
        }
        $result .= '<div class="form-group">
                          <div class="row">
                              <div class="col-6">
                                ' . $history_detail->product_name . ' (x' . $history_detail->quantity . ')
                                ' . $result_aod . '
                                <br>
                                Remark: ' . $remark . '
                                <br>
                                ' . $cook_status . '
                              </div>
                              <div class="col-6" align="right">
                                RM ' . number_format(($history_detail->unit_price + $add_on_price) * $history_detail->quantity, 2) . '
                              </div>
                          </div>
                          <hr>
                        </div>';
      }
      $result .= '<a href="#" class="btn btn-outline-warning print-receipt btn-sm" data-id="' . $history->transaction_no . '">
                          <i class="bi bi-printer"></i> Print
                      </a>';
      if ($history->status == '99') {
        $result .= '&nbsp;&nbsp;
                      <a href="#" class="btn btn-outline-success select-transaction-pay-btn btn-sm" 
                         data-id="' . $history->transaction_no . '" data-table="' . $history->table_id . '">
                          <i class="bi bi-arrow-bar-right"></i> Checkout
                      </a>';
      }

      if ($history->status != '95') {
        $result .= '&nbsp;&nbsp;
                      <a href="#" class="btn select-cancel-transaction-btn btn-outline-danger btn-sm" 
                         data-id="' . $history->transaction_no . '" data-table="' . $history->table_id . '">
                          <i class="bi bi-ban"></i> Cancel
                      </a>';
      }

      if ($history->status != '1') {
        $result .= '&nbsp;&nbsp;
                      <a href="#" class="btn btn-outline-warning select-edit-transaction-btn btn-outline-danger btn-sm" 
                         data-id="' . $history->transaction_no . '" data-table="' . $history->table_id . '">
                          <i class="bi bi-pencil"></i> Edit
                      </a>';
        $result .= '&nbsp;&nbsp;
                      <a href="#" class="btn btn-outline-info select-add-transaction-btn btn-outline-danger btn-sm"
                         data-id="' . $history->transaction_no . '" data-table="' . $history->table_id . '">
                          <i class="bi bi-pencil"></i> Add New Item
                      </a>';
      }

      $result .= '</div>';
    }

    $result .= '</div>';

    return $result;
  }

  public function GetTransactionGrandTotal($id)
  {
    $transaction = Transaction::find($id);
    if (empty($transaction->id)) {
      abort(404);
    }

    if (!empty($transaction->combined_id)) {
      $expTran = explode(', ', $transaction->combined_id);

      $ts = Transaction::whereIn('transaction_no', $expTran)->get();
      $ids = [];
      foreach ($ts as $t) {
        $ids[] = $t->id;
      }
      $detail1 = TransactionDetail::where('transaction_id', $transaction->id)->get();
      $detail2 = TransactionDetail::whereIn('transaction_id', $ids)->get();

      $details = $detail1->concat($detail2);
    } else {
      $details = TransactionDetail::where('transaction_id', $transaction->id)->get();
    }
    $pricing = 0;
    $add_on_price = 0;
    foreach ($details as $detail) {
      $pricing += $detail->unit_price * $detail->quantity;

      $history_add_ons = TransactionAddon::where('details_id', $detail->id)->get();

      foreach ($history_add_ons as $history_add_on) {

        if ($history_add_on->variation_price_type == '2') {
          $add_on_price -= $history_add_on->price * $detail->quantity;
        } else {
          $add_on_price += $history_add_on->price * $detail->quantity;
        }
      }
    }

    return $pricing + $add_on_price;
  }

  public function cashier_checkout(Request $request)
  {
    try {
      \DB::beginTransaction();

      if (!empty($request->paid_amount)) {
        $paid_amount = preg_replace("/[^0-9\.]/", '', $request->paid_amount);
      } else {
        $paid_amount = 0;
      }

      $website_setting = GlobalController::website_setting();

      $new_member = [];
      if (!empty($request->member_phone)) {
        $member = User::where('phone', $request->member_phone)->first();

        if (!empty($member->id)) {
          $new_member = User::find($member->id);
        } else {
          $member_display_code = GlobalController::MemberDisplayCode();

          $new_member = new User();
          $new_member->master_id = "AD000001";
          if (Auth::guard('merchant')->check()) {
            $new_member->dual_master_id = Auth::user()->code;
          }
          $new_member->code = GlobalController::MemberCode();
          $new_member->display_code = $member_display_code[0];
          $new_member->display_running_no = $member_display_code[1];
          $new_member->phone = $request->member_phone;
          $new_member->password = Hash::make($request->member_phone);
          $new_member->status = 1;
          $new_member->save();

          $add_affiliates = GlobalController::add_affiliates($new_member->code, $new_member->master_id);
          if ($add_affiliates != 'ok') {
            throw new \Exception($add_affiliates);
          }
        }
      }

      $get_point_wallet = 0;
      if (!empty($new_member->id)) {
        $get_point_wallet = GlobalController::get_point_wallet($new_member->code);
      }



      $carts = Cart::select('carts.qty', 'carts.sub_category_id as c_sc_id', 'p.*', 'carts.id as cid', 'p.type AS fb_type', 'carts.product_id as c_pid', 'carts.remark')
        ->join('products as p', 'p.id', 'carts.product_id')
        ->where('cashier', '1')
        ->get();

      $totalAmount = 0;
      $totalAddOn = 0;
      foreach ($carts as $cart) {
        if (!empty($cart->c_sc_id)) {
          $exps = explode(',', $cart->c_sc_id);
          foreach ($exps as $exp) {
            $ado_d = ProductVariation::find($exp);
            if ($ado_d->variation_price_type == '2') {
              $totalAddOn -= $ado_d->variation_price * $cart->qty;
            } else {
              $totalAddOn += $ado_d->variation_price * $cart->qty;
            }
          }
        }

        if (!empty($cart->special_price)) {
          $totalAmount += $cart->special_price * $cart->qty;
        } else {
          $totalAmount += $cart->price * $cart->qty;
        }
      }
      $totalDiscount = 0;
      $subtotal = $totalAmount + $totalAddOn;
      $totalAmount = $totalAmount + $totalAddOn;

      if (!empty($request->hidden_discount_amount)) {
        if ($request->hidden_discount_type == 1) {
          $totalDiscount = $totalAmount * $request->hidden_discount_amount / 100;
        } else {
          $totalDiscount = $request->hidden_discount_amount;
        }
      }

      $totalAmount = $totalAmount - $totalDiscount;

      if ($paid_amount >= $totalAmount) {
        $status = '1';
      } else {
        $status = '99';
      }

      if (empty($request->order_no)) {
        $transaction = new Transaction();
      } else {
        $transaction = Transaction::where('transaction_no', $request->order_no)->first();
        $totalAmount = number_format(($totalAmount + $transaction->grand_total), 2, '.', '');
        $transaction->cook_status = null;
      }

      if ($request->used_point == 1 && !empty($new_member->phone)) {

        $rm_to_point = $website_setting->rm_to_point;
        $rm_to_point = !empty($rm_to_point) ? $rm_to_point : 1;

        $transaction->used_point = $get_point_wallet;

        $totalAmount = $totalAmount - ($get_point_wallet * $rm_to_point);
      }

      $transaction->user_id = !empty($new_member) ? $new_member->code : null;
      $transaction->payment_method = $request->payment_method;
      $transaction->paid_amount = $paid_amount;
      $transaction->table_id = $request->table_id;
      $transaction->discount_type = $request->hidden_discount_type;
      $transaction->discount_amount = $request->hidden_discount_amount;
      $transaction->discount = $totalDiscount;
      $transaction->order_type = $request->order_type;
      $transaction->transaction_no = GlobalController::GenerateTransactionNo();
      $transaction->sub_total = $subtotal;
      $transaction->grand_total = number_format($totalAmount, 2, '.', '');
      $transaction->status = $status;
      $transaction->save();

      $items = [];
      $actual_point = 0;

      foreach ($carts as $cart) {
        if (!empty($cart->special_price)) {
          $actual_price = $cart->special_price;
        } else {
          $actual_price = $cart->price;
        }

        $get_point = 0;
        if (!empty($cart->get_point)) {
          if ($cart->get_point_type == 'Percentage') {
            $get_point = $actual_price * $cart->get_point / 100;
          } else {
            $get_point = $cart->get_point;
          }
        }

        $transaction_detail = new TransactionDetail();

        $transaction_detail->transaction_id = $transaction->id;
        $transaction_detail->product_id = $cart->c_pid;
        $transaction_detail->product_type = $cart->fb_type;
        $transaction_detail->item_code = $cart->item_code;
        $transaction_detail->product_code = $cart->product_code;
        $transaction_detail->product_image = $cart->image;
        $transaction_detail->product_name = $cart->product_name;
        $transaction_detail->unit_price = $actual_price;
        $transaction_detail->quantity = $cart->qty;
        $transaction_detail->variation_id = $cart->c_sc_id;
        $transaction_detail->remark = $cart->remark;
        $transaction_detail->get_point = $get_point;
        $transaction_detail->status = 99;
        $transaction_detail->save();
      }

      $add_on = [];
      $select_ds = TransactionDetail::where('transaction_id', $transaction->id)->get();

      foreach ($select_ds as $d) {
        if (!empty($d->variation_id)) {
          $exps = explode(',', $d->variation_id);
          foreach ($exps as $exp) {
            $ado_d = ProductVariation::find($exp);
            $ado_t = VariationTitle::find($ado_d->variation_title_id);
            $add_on_detail = new TransactionAddon();
            $add_on_detail->details_id = $d->id;
            $add_on_detail->add_on_type = $ado_t->variation_type_two;
            $add_on_detail->transaction_id = $transaction->id;
            $add_on_detail->add_on_id = $ado_d->id;
            $add_on_detail->add_on_title = $ado_t->variation_title;
            $add_on_detail->add_on_name = $ado_d->variation_name;
            $add_on_detail->price_type = $ado_d->variation_price_type;
            $add_on_detail->price = $ado_d->variation_price;
            $add_on_detail->qty = $d->quantity;
            $add_on_detail->status = '99';
            $add_on_detail->save();
          }
        }
      }
      $delete_cart = Cart::where('cashier', '1')->delete();

      \DB::commit();

      return "ok";
    } catch (\Exception $e) {
      \DB::rollback();
      return $e->getMessage();
    } catch (\Error $e) {
      \DB::rollback();
      return $e->getMessage();
    }
  }

  public function SearchTableHistory(Request $request)
  {
    if (!empty($request->search_date)) {
      $searchDate = date('Y-m-d', strtotime($request->search_date));
    } else {
      $searchDate = date('Y-m-d');
    }

    $histories = Transaction::where('table_id', $request->id)
      ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), $searchDate)
      ->orderBy('created_at', 'desc')
      ->get();
    $result = "";
    foreach ($histories as $history) {
      $totalGrand = $this->GetTransactionGrandTotal($history->id);
      if (!empty($history->combined_id)) {
        $totalGrand = $totalGrand - $history->discount;
      }
      if ($history->status == '1') {
        $payment_status = '<label class="badge bg-success">Paid</label>';
      } elseif ($history->status == '95') {
        $payment_status = '<label class="badge bg-danger">Cancelled</label>';
      } else {
        $payment_status = '<label class="badge bg-danger">Unpaid</label>';
      }

      if (!empty($history->combined_id)) {
        $combine_receipt = ", " . $history->combined_id;
      } else {
        $combine_receipt = "";
      }

      $result .= '<div class="form-group container-box">
                        <div class="history-header">
                          <div class="row">
                            <div class="col-6">
                              ' . $history->transaction_no . ' ' . $combine_receipt . '
                              <br>
                              ' . $payment_status . '
                            </div>
                            <div class="col-6" align="right">
                              Total: <h4>RM ' . number_format($totalGrand, 2) . '</h4>
                            </div>
                          </div>
                        </div>
                        <hr>';
      if (!empty($history->combined_id)) {
        $expTran = explode(', ', $history->combined_id);
        $ts = Transaction::whereIn('transaction_no', $expTran)->get();
        $ids = [];
        foreach ($ts as $t) {
          $ids[] = $t->id;
        }

        $detail1 = TransactionDetail::select('transaction_details.*', 't.transaction_no')
          ->join('transactions as t', 't.id', 'transaction_details.transaction_id')
          ->where('transaction_id', $history->id)
          ->get();

        $detail2 = TransactionDetail::select('transaction_details.*', 't.transaction_no')
          ->join('transactions as t', 't.id', 'transaction_details.transaction_id')
          ->whereIn('transaction_id', $ids)
          ->get();

        $history_details = $detail1->concat($detail2);
      } else {
        $history_details = TransactionDetail::select('transaction_details.*')
          ->join('transactions as t', 't.id', 'transaction_details.transaction_id')
          ->where('transaction_id', $history->id)
          ->get();
      }

      foreach ($history_details as $history_detail) {
        $history_add_ons = TransactionAddon::where('details_id', $history_detail->id)->get();
        $result_aod = "";
        $add_on_price = 0;
        if ($history_detail->status == '99') {
          $cook_status = '<label class="badge bg-danger">Not Ready</label>';
        } elseif ($history_detail->status == '1') {
          $cook_status = '<label class="badge bg-info">Preparing</label>';
        } else {
          $cook_status = '<label class="badge bg-success">Ready</label>';
        }

        if (!empty($history_detail->transaction_no)) {
          $transaction_no = $history_detail->transaction_no . "<br>";
        } else {
          $transaction_no = "";
        }

        if (!empty($history_detail->remark)) {
          $remark = $history_detail->remark;
        } else {
          $remark = "Empty(No Remark)";
        }

        foreach ($history_add_ons as $history_add_on) {
          if ($history_add_on->price_type == '2') {
            $result_aod .= '<p>
                                    <small>
                                       - ' . $history_add_on->add_on_title . ' - ' . $history_add_on->add_on_name . ' (' . number_format($history_add_on->price, 2) . ')
                                    </small>
                                  </p>';
          } else {
            $result_aod .= '<p>
                                    <small>
                                       + ' . $history_add_on->add_on_title . ' - ' . $history_add_on->add_on_name . ' (' . number_format($history_add_on->price, 2) . ')
                                    </small>
                                  </p>';
          }
          if ($history_add_on->price_type == '2') {
            $add_on_price -= $history_add_on->price;
          } else {
            $add_on_price += $history_add_on->price;
          }
        }
        $result .= '<div class="form-group">
                          <div class="row">
                              <div class="col-6">
                                ' . $transaction_no . '
                                ' . $history_detail->product_name . ' (x' . $history_detail->quantity . ')
                                ' . $result_aod . '
                                <br>
                                Remark: ' . $remark . '
                                <br>
                                <br>
                                ' . $cook_status . '
                              </div>
                              <div class="col-6" align="right">
                                RM ' . number_format(($history_detail->unit_price + $add_on_price) * $history_detail->quantity, 2) . '
                              </div>
                          </div>
                          <hr>
                        </div>';
      }
      $result .= '<a href="#" class="btn btn-outline-warning print-receipt btn-sm" data-id="' . $history->transaction_no . '">
                          <i class="bi bi-printer"></i> Print
                      </a>';
      if ($history->status == '99') {
        $result .= '&nbsp;&nbsp;
                      <a href="#" class="btn btn-outline-success select-transaction-pay-btn btn-sm" 
                         data-id="' . $history->transaction_no . '" data-table="' . $history->table_id . '">
                          <i class="bi bi-arrow-bar-right"></i> Checkout
                      </a>';
      }

      if ($history->status != '95') {
        $result .= '&nbsp;&nbsp;
                      <a href="#" class="btn select-cancel-transaction-btn btn-outline-danger btn-sm" 
                         data-id="' . $history->transaction_no . '" data-table="' . $history->table_id . '">
                          <i class="bi bi-ban"></i> Cancel
                      </a>';
      }

      if ($history->status != '1') {
        $result .= '&nbsp;&nbsp;
                      <a href="#" class="btn select-edit-transaction-btn btn-outline-info btn-sm" 
                         data-id="' . $history->transaction_no . '" data-table="' . $history->table_id . '">
                          <i class="bi bi-pencil"></i> Edit
                      </a>';
        $result .= '&nbsp;&nbsp;
                      <a href="#" class="btn select-add-transaction-btn btn-outline-info btn-sm"
                         data-id="' . $history->transaction_no . '" data-table="' . $history->table_id . '">
                          <i class="bi bi-pencil"></i> Add New Item
                      </a>';
      }

      $result .= '</div>
                    </div>';
    }

    return $result;
  }

  public function checkOrderAmount(Request $request)
  {
    $transaction = Transaction::where('transaction_no', $request->order_no)->first();

    return number_format($transaction->grand_total, 2, '.', '');
  }

  public function GetTransaction(Request $request)
  {
    $transaction = Transaction::where('transaction_no', $request->transaction_no)->first();
    $details = TransactionDetail::where('transaction_id', $transaction->id)->get();

    $result = '<h4>Edit Order #' . $transaction->transaction_no . '</h4>
                    <input type="hidden" name="transaction_no" value="' . $transaction->transaction_no . '">
                    <hr>
                    <div class="container-box form-group">
                      <div class="row">
                        <div class="col-6">
                          Item(s)
                        </div>
                        <div class="col-5" align="center">
                          Quantity
                        </div>
                        <div class="col-1" align="right">
                          Action
                        </div>
                      </div>
                   </div>';
    $result .= '<div class="form-group container-box">';
    $totalDetails = count($details);
    foreach ($details as $detail) {
      $history_add_ons = TransactionAddon::where('details_id', $detail->id)->get();
      $result_aod = "";
      $add_on_price = 0;
      $stockBalance = HomeController::BalanceQuantity($detail->product_id);
      foreach ($history_add_ons as $history_add_on) {
        $result_aod .= '<p>
                              <small>
                                 + ' . $history_add_on->add_on_title . ' - ' . $history_add_on->add_on_name . ' (' . number_format($history_add_on->price, 2) . ')
                              </small>
                            </p>';
        $add_on_price += $history_add_on->price;
      }
      $deleteBtn = "";
      if ($totalDetails > 1) {
        $deleteBtn = '<a href="#" class="edit-list-delete-btn" style="color: red;">
                            <i class="bi bi-trash"></i>
                          </a>';
      }
      $result .= '<div class="parent-box">
                      <input type="hidden" name="did[]" value="' . $detail->id . '">
                      <div class="row">
                        <div class="col-6">
                          ' . $detail->product_name . '
                          ' . $result_aod . '
                        </div>
                        <div class="col-5" align="center">
                          <div class="form-group quantity-setting">
                              <button class="btn btn-primary deduct-qty-button">
                                <i class="bi bi-dash"></i>
                              </button>
                              <input type="text" class="form-control" name="quantity[]" value="' . $detail->quantity . '" onkeypress="return isNumberKey(event)">
                              <input type="hidden" name="balance_quantity[]" value="' . $stockBalance . '">
                            </div>
                        </div>
                        <div class="col-1" align="right">
                            ' . $deleteBtn . '
                        </div>
                      </div>
                    <hr>
                    </div>';
    }
    $result .= '</div>';

    return $result;
  }

  public function GetAvailableTable()
  {
    $tables = RTable::where('status', '1')->get();

    $result = '<div class="row">
                      <div class="col-md-6">
                          <label>Transfer From</label>
                          <select class="form-control transfer_from" name="transfer_from">';
    foreach ($tables as $table) {
      $transaction = Transaction::where('table_id', $table->id)
        ->where('status', '99')
        // ->where('status', '!=', '1')
        ->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), date('Y-m-d'))
        ->orderBy('created_at', 'desc')
        ->first();
      $qrcodes = QrCode::where('table_id', $table->id)
        ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), date('Y-m-d'))
        ->first();
      if (!empty($transaction->id) || !empty($qrcodes->id)) {
        $result .=  '<option value="' . $table->id . '">' . $table->table_name . '</option>';
      }
    }
    $result .=  '</select>
                      </div>
                      <div class="col-md-6">
                          <label>Transfer To</label>
                          <select class="form-control transfer_to" name="transfer_to">';
    foreach ($tables as $table) {
      $transaction = Transaction::where('table_id', $table->id)
        ->where('status', '99')
        // ->where('status', '!=', '1')
        ->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), date('Y-m-d'))
        ->orderBy('created_at', 'desc')
        ->first();
      $qrcodes = QrCode::where('table_id', $table->id)
        ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), date('Y-m-d'))
        ->first();
      if (empty($transaction->id) && empty($qrcodes->id)) {
        $result .=  '<option value="' . $table->id . '">' . $table->table_name . '</option>';
      }
    }
    $result .= '</select>
                    </div>
                  </div>';


    return $result;
  }

  public function TransferTable(Request $request)
  {

    $qrcode = QrCode::where('table_id', $request->transfer_from)->first();
    if (!empty($qrcode->id)) {
      $update = QrCode::find($qrcode->id);
      $update = $update->update(['table_id' => $request->transfer_to]);
    }

    $transfer_from = Transaction::where('table_id', $request->transfer_from)
      ->where('status', '99')
      ->orderBy('created_at', 'desc')
      ->update(['table_id' => $request->transfer_to]);
  }

  public function GetOrders()
  {
    $transactions = Transaction::select('t.table_name', 'transactions.*')
      ->join('r_tables as t', 't.id', 'transactions.table_id')
      ->where('transactions.status', '99')
      ->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), date('Y-m-d'))
      ->get();

    $combine_result = '<div class="row">';
    foreach ($transactions as $transaction) {
      $totalPrice = number_format($transaction->grand_total, 2);
      $combine_result .= '<div class="col-md-6 selected-transaction" data-id="' . $transaction->transaction_no . '">
                                  <div class="form-group">
                                    <div class="container-box">
                                        <div class="row">
                                            <div class="col-6">
                                                Order No: ' . $transaction->transaction_no . '
                                                Order Date: ' . $transaction->created_at . '<br>
                                                Total Price: RM' . $totalPrice . '
                                            </div>
                                            <div class="col-6">
                                                Table No. ' . $transaction->table_name . '
                                            </div>
                                        </div>
                                    </div>  
                                  </div>
                                </div>';
    }
    $combine_result .= '</div>';

    return $combine_result;
  }

  public function CombineReceipt(Request $request)
  {
    $exTran = explode(',', $request->selected_transaction_no);

    $transactions = Transaction::whereIn('transaction_no', $exTran)->get();
    $totalAmount = 0;
    foreach ($transactions as $transaction) {
      $totalAmount += $transaction->grand_total;
    }

    return $totalAmount;
  }

  public function CombineReceiptSubmit(Request $request)
  {
    $exTran = explode(',', $request->transaction_no);
    $combine_no = $this->getCombineNo();
    $transactions = Transaction::whereIn('transaction_no', $exTran)->orderBy('transaction_no')->get();

    foreach ($transactions as $key => $transaction) {
      $a = $exTran;
      $b = array_search($transaction->transaction_no, $a);
      unset($a[$b]);
      if ($request->payment_method == '1') {
        $paid_amount = $request->cash_amount;
      } else {
        $paid_amount = $request->totalAmount;
      }
      $c = implode(', ', $a);
      $reference_number = !empty($request->cc_reference_number) ? $request->cc_reference_number : $request->reference_number;
      $qr_pay_id = "";
      $cc_bank_id = "";
      if ($request->payment_method == '2') {
        $qr_pay_id = $request->qr_type;
        $cc_bank_id = "";
      } elseif ($request->payment_method == '3') {
        $qr_pay_id = "";
        $cc_bank_id = $request->bank_name;
      }



      $update = Transaction::where('transaction_no', $transaction->transaction_no)->update([
        'combined_id' => $c,
        'discount' => $request->totalDiscount,
        'discount_type' => $request->payment_discount_type,
        'discount_amount' => $request->combine_payment_discount_amount,
        'status' => '1',
        'payment_method' => $request->payment_method,
        'paid_amount' => $paid_amount,
        'reference_number' => $reference_number,
        'cc_bank_id' => $cc_bank_id,
        'qr_pay_id' => $qr_pay_id,
        'combined_no' => $combine_no
      ]);

      $this->removeQrSection($transaction->transaction_no);
    }
    if (!empty($transaction->transaction_no)) {
      return array("ok", $transaction->transaction_no);
    } else {
      return array("not ok", "");
    }
  }

  public function getCombineNo()
  {
    $transaction = Transaction::select(DB::raw('MAX(combined_no) as lastNo'))->first();
    $CombineNo = $transaction->lastNo + 1;

    return $CombineNo;
  }

  public function GetTransactionList(Request $request)
  {
    $search_transaction_no = !empty($request->search_value) ? $request->search_value: '';
    $search_date = !empty($request->date) ? $request->date: '';

    $today = strtotime("today");
    $todayFormat = date('yyyy-MM-dd', $today);
    $transactions = Transaction::select(
      DB::raw('COALESCE(COALESCE(CONCAT(m.f_name, " ", m.l_name), CONCAT(s.f_name, " ", s.l_name)), CONCAT(a.f_name, " ", a.l_name)) AS customer_name'),
      'transactions.transaction_no',
      'd.product_name',
      'd.product_image',
      'unit_price',
      'd.quantity',
      'total_amount',
      'transactions.status',
      'transactions.created_at',
      'd.sub_category',
      'p.description',
      'transactions.id AS Tid',
      'transactions.grand_total',
      'transactions.shipping_fee',
      'transactions.processing_fee',
      'transactions.completed',
      'transactions.address_name',
      'm.code as Acode',
      's.code as Ccode',
      'a.code as ADcode',
      'order_type',
      'delivery_type'
    )
      ->leftJoin('agents AS m', 'm.code', 'transactions.user_id')
      ->leftJoin('users AS s', 's.code', 'transactions.user_id')
      ->leftJoin('admins AS a', 'a.code', 'transactions.user_id')
      ->join('transaction_details AS d', 'd.transaction_id', 'transactions.id')
      ->join('products as p', 'd.product_id', 'p.id')
      ->whereIn('transactions.status', ['1', '95'])
      ->groupBy('transactions.id')
      ->orderBy('transactions.created_at', 'desc');

      if($search_transaction_no){
        $transactions = $transactions->where('transactions.transaction_no', $search_transaction_no);
      }

      if($search_date){
        $transactions = $transactions->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), $search_date);
      }else{
        $transactions = $transactions->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), date('Y-m-d'));
      }
      
      $transactions = $transactions->get();
    
    $data = GlobalController::get_translations();
    $Transaction_Number_translation = isset($data['backendlang']['backendlang']['Transaction_Number']) ? $data['backendlang']['backendlang']['Transaction_Number'] :'';

    $result =            '<div class="col-md-4">
                              <div class="form-group container-box">
                                  <input type="date" class="form-control datetimepicker" name="time-query" style="height:34px;" value="' . date('Y-m-d') . '">
                              </div>
                          </div>
                          <div class="col-md-8">
                            <div class="form-group container-box">
                              <div class="input-group">
                                <input type="text" name="search_transaction" class="form-control transaction-query" value="' . htmlspecialchars($search_transaction_no) . '" placeholder="'.$Transaction_Number_translation.'" style="height: 34px;">
                                <span class="input-group-btn">
                                    <button type="submit" class="btn btn-primary btn-white py-1 px-3 search-button" style="outline: none;">
                                        <span class="ace-icon fa fa-search icon-on-right bigger-110"></span>
                                        '. (isset($data['backendlang']['backendlang']['Search']) ? $data['backendlang']['backendlang']['Search'] :'') .'
                                    </button>
                                </span>
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-12">
                            <div class="table-responsive">
                              <table class="table table-bordered">
                                <thead>
                                  <tr class="info">
                                    <th>#</th>
                                    <th>'. (isset($data['backendlang']['backendlang']['Transaction_No']) ? $data['backendlang']['backendlang']['Transaction_No'] :'') .'</th>
                                    <th>'. (isset($data['backendlang']['backendlang']['Net_Amount']) ? $data['backendlang']['backendlang']['Net_Amount'] :'') .' (RM)</th>
                                    <th>'. (isset($data['backendlang']['backendlang']['Total_Amount']) ? $data['backendlang']['backendlang']['Net_Amount'] :'') .' (RM)</th>
                                    <th>'. (isset($data['backendlang']['backendlang']['Status']) ? $data['backendlang']['backendlang']['Status'] :'') .'</th>
                                    <th>'. (isset($data['backendlang']['backendlang']['Created_Date']) ? $data['backendlang']['backendlang']['Created_Date'] :'') .'</th>
                                    <th></th>
                                  </tr>
                                </thead>
                                <tbody>';
    $totalTransaction = 0;
    // if(!$transactions->isEmpty()){
    if (!empty($transactions)) {
      foreach ($transactions as $key => $transaction) {
        $product = TransactionDetail::select('transaction_details.*', 'p.*', 'transaction_details.quantity', 'p.description', 'transaction_details.unit_price', 'transaction_details.total_amount')
          ->where('transaction_details.transaction_id', $transaction->Tid)
          ->join('products as p', 'transaction_details.product_id', 'p.id')
          ->get();


        $actualAmount = number_format($transaction->grand_total - $transaction->shipping_fee - $transaction->processing_fee, 2);
        $shippingFee = number_format($transaction->shipping_fee, 2);
        $processingFee = number_format($transaction->processing_fee, 2);
        $grandTotal = number_format($transaction->grand_total, 2);
        $actualKey = $key + 1;

        $result .=                       '<tr data-toggle="collapse" data-target="#info' . $transaction->Tid . '" class="clickable">
                                        <td>'
          . $actualKey;
        $result .=                            '<input type="hidden" name="tid" value="' . $transaction->Tid . '">
                                        </td>
                                        <td>'
          . $transaction->transaction_no;
        $result .=                         '</td>';

        $result .=                         '<td>'
          . $actualAmount;
        $result .=                         '</td>';
        $result .=                          '<td>'
          . $grandTotal;
        $result .=                         '</td>
                                        <td>';
        if ($transaction->status == 99) {
          $result .=                             '<span class="badge bg-warning">'.(isset($data['backendlang']['backendlang']['Unpaid']) ? $data['backendlang']['backendlang']['Unpaid'] : 'Unpaid').'</span>';
        } elseif ($transaction->status == 98) {
          $result .=                             '<span class="badge bg-info">'.(isset($data['backendlang']['backendlang']['Waiting_Verification']) ? $data['backendlang']['backendlang']['Waiting_Verification'] : 'Waiting Verification').'</span>';
          
        } elseif ($transaction->status == 97) {
          $result .=                             '<span class="badge bg-info">'.(isset($data['backendlang']['backendlang']['In_progress']) ? $data['backendlang']['backendlang']['In_progress'] : 'In-progress').'</span>';
        } elseif ($transaction->status == '96') {
          $result .=                             '<span class="badge bg-danger">'.(isset($data['backendlang']['backendlang']['Rejected']) ? $data['backendlang']['backendlang']['Rejected'] : 'Rejected').'</span>';
        } elseif ($transaction->status == 1) {
          if ($transaction->completed == 1) {
            $result .=                                '<span class="badge bg-success">'.(isset($data['backendlang']['backendlang']['Delivered']) ? $data['backendlang']['backendlang']['Delivered'] : 'Delivered').'</span>';
          } else {
            $result .=                                '<span class="badge bg-success">'.(isset($data['backendlang']['backendlang']['Paid']) ? $data['backendlang']['backendlang']['Paid'] : 'Paid').'</span>';
          }
        } elseif ($transaction->status == 22) {
          $result .=                               '<span class="badge bg-warning">'.(isset($data['backendlang']['backendlang']['Refunded']) ? $data['backendlang']['backendlang']['Refunded'] : 'Refunded').'</span>';
        } else {
          $result .=                              '<span class="badge bg-danger">'.(isset($data['backendlang']['backendlang']['Cancelled']) ? $data['backendlang']['backendlang']['Cancelled'] : 'Cancelled').'</span>';
        }
        $result .=                         '</td>
                                        <td>'
          . $transaction->created_at;
        $result .=                         '</td>
                                        <td>';
        $result .=                            '&nbsp;&nbsp;';
        if ($transaction->status == '1') {
          $result .=                           '<div class="btn-group">
                                            <button type="button" class="btn btn-outline-primary btn-default btn-sm  dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                              '.(isset($data['backendlang']['backendlang']['Action']) ? $data['backendlang']['backendlang']['Action'] : 'Action').' <span class="caret"></span>
                                            </button>

                                              <ul class="dropdown-menu" role="menu">
                                                  <li><a href="#" class="refund_action">'.(isset($data['backendlang']['backendlang']['Cancel']) ? $data['backendlang']['backendlang']['Cancel'] : 'Cancel').'</a></li>
                                              </ul>

                                            
                                          </div>';
        }
        if ($transaction->status == '98') {
          $result .=                           '<div class="btn-group">
                                            <button type="button" class="btn btn-outline-primary btn-default btn-sm  dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                              '.(isset($data['backendlang']['backendlang']['Action']) ? $data['backendlang']['backendlang']['Action'] : 'Action').' <span class="caret"></span>
                                            </button>

                                            <ul class="dropdown-menu" role="menu">
                                                  <li><a href="#" class="change_action" data-id="1">'.(isset($data['backendlang']['backendlang']['Approve']) ? $data['backendlang']['backendlang']['Approve'] : 'Approve').'</a></li>
                                                  <li><a href="#" class="change_action" data-id="96">'.(isset($data['backendlang']['backendlang']['Reject']) ? $data['backendlang']['backendlang']['Reject'] : 'Reject').'</a></li>
                                            </ul>
                                          </div>';
        }
        $result .=                           '&nbsp;&nbsp;';

        $result .=                           '&nbsp;&nbsp;';
        $result .=                         '</td>
                                      </tr>
                                      <tr>                                 
                                          <td colspan="10">
                                              <div id="info' . $transaction->Tid . '" class="collapse" style="padding: 20px;">';
        $total = 0;
        foreach ($product as $everyProduct) {
          $currentAmount = $everyProduct->total_amount;
          $actualDesc = htmlspecialchars_decode($everyProduct->description);
          $result .=                                      (isset($data['backendlang']['backendlang']['Product']) ? $data['backendlang']['backendlang']['product'] : 'Product') . ': ' . $everyProduct->product_name . '<br>';
          $result .=                                      (isset($data['backendlang']['backendlang']['Price']) ? $data['backendlang']['backendlang']['Price'] : 'Price') . ': ' . number_format($everyProduct->unit_price, 2) . ' <br>
                                                    ' . (isset($data['backendlang']['backendlang']['Quantity']) ? $data['backendlang']['backendlang']['Quantity'] : 'Quantity') . ': x' . $everyProduct->quantity . ' <br><br>
                                                    ';
          $total = $total + $currentAmount;
        }
        $result .=                                '</div>';
        $result .=                             '</td>';
        $result .=                          '</tr>';
        if ($transaction->status == '1') {
          $totalTransaction += $transaction->grand_total;
        }
      }
    } else {
      $result .=                   '<tr>
                                    <td colspan="10">'.(isset($data['backendlang']['backendlang']['No_Result_Found']) ? $data['backendlang']['backendlang']['No_Result_Found'] : 'No Result Found').'</td>
                                  </tr>';
    }
    $result .=                 '</tbody>
                              </table>
                              </div>';
    $result .=             '</div>
                          </div>';



    return array($result, $search_transaction_no, $search_date);
  }


  public function SelectItemsCook(Request $request)
  {
    if (!empty($request->selected_id)) {
      $exp = explode(',', $request->selected_id);
      $tds = TransactionDetail::whereIn('id', $exp)->update(['status' => $request->status]);
    }

    if (!empty($request->selected_id_addon)) {
      $exp = explode(',', $request->selected_id_addon);
      $ads = TransactionAddon::whereIn('id', $exp)->update(['status' => $request->status]);
    }
  }

  public function updateTransaction(Request $request)
  {
    $tds = Transaction::where('id', $request->id)->update(['cook_status' => '1']);
  }

  public function getAllOrder()
  {
    $transactions = Transaction::select('transactions.*', 't.table_name')
      ->leftJoin('r_tables as t', 't.id', 'transactions.table_id')
      ->where(function ($query) {
        $query->where('order_type', '!=', '3');
        $query->whereNotIn('transactions.status', ['98', '95']);
        $query->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), date('Y-m-d'));
        $query->whereNull('transactions.cook_status');
      })
      ->orWhere(function ($query) {
        $query->where('order_type', '=', '3');
        $query->where('transactions.status', '=', '1');
        $query->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), date('Y-m-d'));
        $query->whereNull('transactions.cook_status');
      })
      ->orderBy('transactions.created_at', 'asc')
      ->get();
    $transactionID = [];
    foreach ($transactions as $key => $value) {
      $transactionID[] = $value->id;
    }
    return $transactionID;
  }

  public function getUncookOrder()
  {
    $transactions = Transaction::select('d.product_name', 'transactions.transaction_no')
      ->join('transaction_details as d', 'd.transaction_id', 'transactions.id')
      ->where(function ($query) {
        $query->where('order_type', '!=', '3');
        $query->whereNotIn('transactions.status', ['98', '95']);
        $query->where('d.status', '=', '99');
        $query->where('product_type', '1');
        $query->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), date('Y-m-d'));
        $query->whereNull('transactions.cook_status');
      })
      ->orWhere(function ($query) {
        $query->where('order_type', '=', '3');
        $query->where('transactions.status', '=', '1');
        $query->where('d.status', '=', '1');
        $query->where('product_type', '1');
        $query->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), date('Y-m-d'));
        $query->whereNull('transactions.cook_status');
      })
      ->groupBy('transactions.id')
      ->orderBy('transactions.created_at', 'asc')
      ->get();
    return count($transactions);
  }



  public function getUnserveOrder()
  {
    $transactions = Transaction::select('d.product_name', 'transactions.transaction_no')
      ->join('transaction_details as d', 'd.transaction_id', 'transactions.id')
      ->where(function ($query) {
        $query->where('order_type', '!=', '3');
        $query->whereNotIn('transactions.status', ['98', '95']);
        $query->where('d.status', '=', '99');
        $query->where('product_type', '2');
        $query->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), date('Y-m-d'));
        $query->whereNull('transactions.cook_status');
      })
      ->orWhere(function ($query) {
        $query->where('order_type', '=', '3');
        $query->where('transactions.status', '=', '1');
        $query->where('d.status', '=', '1');
        $query->where('product_type', '2');
        $query->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), date('Y-m-d'));
        $query->whereNull('transactions.cook_status');
      })
      ->groupBy('transactions.id')
      ->orderBy('transactions.created_at', 'asc')
      ->get();
    return count($transactions);
  }

  public function getAllOrderDetail()
  {
    $transactions = Transaction::select('transactions.*', 't.table_name', 'd.id as did')
      ->join('transaction_details as d', 'd.transaction_id', 'transactions.id')
      ->leftJoin('r_tables as t', 't.id', 'transactions.table_id')
      ->where(function ($query) {
        $query->where('order_type', '!=', '3');
        $query->whereNotIn('transactions.status', ['98', '95']);
        $query->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), date('Y-m-d'));
        $query->where('d.status', '!=', '2');
      })
      ->orWhere(function ($query) {
        $query->where('order_type', '=', '3');
        $query->where('transactions.status', '=', '1');
        $query->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), date('Y-m-d'));
        $query->where('d.status', '!=', '2');
      })

      ->orderBy('transactions.created_at', 'asc')
      ->get();
    $transactionID = [];
    foreach ($transactions as $key => $value) {
      $transactionID[] = $value->did;
    }
    return $transactionID;
  }

  public function AddInOrder(Request $request)
  {
    $transaction = Transaction::select('transactions.*', 't.table_name', 's.name as state_name')
      ->leftJoin('r_tables as t', 't.id', 'transactions.table_id')
      ->leftJoin('states as s', 's.id', 'transactions.state')
      ->whereNull('transactions.cook_status')
      ->where('transactions.id', $request->id)
      ->orderBy('transactions.created_at', 'asc')
      ->first();

    $details = TransactionDetail::where('transaction_id', $transaction->id)
      ->where('status', '!=', '2')
      ->where('product_type', $request->type)
      ->get();
    $result = "";
    if (!$details->isEmpty()) {
      $address = "";
      if ($transaction->order_type == 1) {
        $type = '<span class="label label-info">
                            Dine in
                         </span>';
      } elseif ($transaction->order_type == 2) {
        $type = '<span class="label label-warning">
                            Take away
                         </span>';
      } else {
        if ($transaction->delivery_type == '1') {
          $type = '<span class="label label-danger">
                              Pick-up
                         </span>';
          $address = '<div class="form-group">
                              Name: ' . $transaction->address_name . ' <br>
                              Phone: ' . $transaction->phone . '
                            </div>';
        } else {
          $type = '<span class="label label-danger">
                              Delivery
                           </span>';
          $address = '<div class="form-group">
                              Name: ' . $transaction->address_name . ' <br>
                              Address: ' . $transaction->address . ', <br>
                                       ' . $transaction->postcode . ', ' . $transaction->city . '<br>
                                       ' . $transaction->state_name . '<br>
                              Phone: ' . $transaction->phone . '
                            </div>';
        }
      }

      $result .= '<div class="col-md-4">
                          <div class="container-box">
                            <input type="hidden" class="tid" value="' . $transaction->id . '">
                            <h3 style="margin-top: 0px;">#' . $transaction->transaction_no . '</h3>
                            <hr>
                            <div class="row">
                              <div class="col-6">
                                <p>' . $transaction->table_name . '</p>
                              </div>
                              <div class="col-6" align="right">
                                ' . $type . '
                              </div>
                            </div>
                            ' . $address . '
                            <hr>
                            <div class="list-group">';

      foreach ($details as $d) {
        $p_status = ($d->status == '1') ? 'preparing' : '';
        if ($d->status == '99') {
          $c_status = '<span class="badge bg-info">Pending</span>';
        } else {
          $c_status = '<span class="badge bg-success">On-going</span>';
        }
        $result .= '<a href="#" class="list-group-item food-items ' . $p_status . '" data-filter="' . $d->id . '">
                            <div class="row">
                              <div class="col-6">
                                ' . $d->product_name . ' (x' . $d->quantity . ')
                              </div>
                              <div class="col-6" align="right">
                                <div class="form-group">
                                  ' . $c_status . '
                                </div>
                              </div>
                            </div>';

        $add_on_details = TransactionAddon::select('t.variation_title', 'transaction_addons.*')
          ->join('product_variations AS v', 'v.id', 'transaction_addons.add_on_id')
          ->join('variation_titles as t', 't.id', 'v.variation_title_id')
          ->where('details_id', $d->id)
          ->where('add_on_type', '1')
          ->get();
        foreach ($add_on_details as $add_on) {
          $result .= '<p style="margin: 0px;">
                              <small style="font-size: 13px; font-weight: bold; color: red;">
                                + ' . $add_on->add_on_title . ' - ' . $add_on->add_on_name . '
                              </small>
                            </p>';
        }
        $result .= '<h6>Remark: </h6>
                          <br>
                          <span style="font-size: 13px; font-weight: bold; color: red;">
                          ' . $d->remark . '
                          </span>
                          </a>';
      }
      $result .= '</div>
                       <hr>
                       <button class="btn btn-warning select-all-btn">Select All</button>
                       <button class="btn btn-outline-warning deselect-all-btn">Deselect All</button>
                       <button class="btn btn-outline-success cook-btn">Cook</button>
                       <button class="btn btn-outline-primary ready-btn">Ready</button>
                  </div>
              </div>';
    }


    return $result;
  }

  public function AddInOrderDetail(Request $request)
  {
    $d = TransactionDetail::where('id', $request->did)
      ->where('status', '!=', '2')
      ->first();

    $p_status = ($d->status == '1') ? 'preparing' : '';
    if ($d->status == '99') {
      $c_status = '<span class="badge bg-info">Pending</span>';
    } else {
      $c_status = '<span class="badge bg-success">On-going</span>';
    }

    $result = '<a href="#" class="list-group-item food-items ' . $p_status . '" data-filter="' . $d->id . '">
                            <div class="row">
                              <div class="col-6">
                                ' . $d->product_name . ' (x' . $d->quantity . ')
                              </div>
                              <div class="col-6" align="right">
                                <div class="form-group">
                                  ' . $c_status . '
                                </div>
                              </div>
                            </div>';

    $add_on_details = TransactionAddon::select('t.variation_title', 'transaction_addons.*')
      ->join('product_variations AS v', 'v.id', 'transaction_addons.add_on_id')
      ->join('variation_titles as t', 't.id', 'v.variation_title_id')
      ->where('details_id', $d->id)
      ->where('add_on_type', '1')
      ->get();
    foreach ($add_on_details as $add_on) {
      $result .= '<p style="margin: 0px;">
                        <small style="font-size: 13px; font-weight: bold; color: red;">
                          + ' . $add_on->add_on_title . ' - ' . $add_on->add_on_name . '
                        </small>
                      </p>';
    }
    $result .= '<h6>Remark: </h6>
                    <br>
                    <span style="font-size: 13px; font-weight: bold; color: red;">
                    ' . $d->remark . '
                    </span>
                    </a>';

    return array($d->transaction_id, $result);
  }

  public function RemoveCancelledOrder(Request $request)
  {
    $transaction = Transaction::find($request->tid);
    if ($transaction->status == '95') {
      return 0;
    } else {
      return 1;
    }
  }

  public function CheckTransactionDetail(Request $request)
  {
    $detail = TransactionDetail::find($request->did);

    if (!empty($detail->id)) {
      return 1;
    } else {
      return 0;
    }
  }


  public function AddInOrderBeverage(Request $request)
  {
    $transaction = Transaction::select('transactions.*', 't.table_name', 's.name as state_name')
      ->leftJoin('r_tables as t', 't.id', 'transactions.table_id')
      ->leftJoin('states as s', 's.id', 'transactions.state')
      ->whereNull('transactions.cook_status')
      ->where('transactions.id', $request->id)
      ->orderBy('transactions.created_at', 'asc')
      ->first();

    $details = TransactionDetail::select(
      'transaction_details.*',
      'transaction_details.quantity AS td_qty',
      'transaction_details.id AS td_id',
      'transaction_details.status as td_status'
    )
      ->where('transaction_id', $transaction->id)
      ->where('status', '!=', '2')
      ->where('product_type', '2')
      ->get();

    $asd = TransactionAddon::select(
      't.variation_title',
      'transaction_addons.*',
      'transaction_addons.qty as ad_qty',
      'transaction_addons.id as ta_id',
      'transaction_addons.status as ta_status'
    )
      ->join('product_variations AS v', 'v.id', 'transaction_addons.add_on_id')
      ->join('variation_titles as t', 't.id', 'v.variation_title_id')
      ->where('transaction_addons.transaction_id', $transaction->id)
      ->where('add_on_type', '2')
      ->where('transaction_addons.status', '!=', '2')
      ->get();

    $details = $details->concat($asd);

    $details = array_reverse(array_sort($details, function ($value) {
      return $value['created_at'];
    }));

    $result = "";
    $address = "";
    if ($transaction->order_type == 1) {
      $type = '<span class="label label-info">
                            Dine in
                         </span>';
    } elseif ($transaction->order_type == 2) {
      $type = '<span class="label label-warning">
                            Take away
                         </span>';
    } else {
      $type = '<span class="label label-danger">
                            Delivery
                         </span>';
      $address = '<div class="form-group">
                            Name: ' . $transaction->address_name . ' <br>
                            Address: ' . $transaction->address . ', <br>
                                     ' . $transaction->postcode . ', ' . $transaction->city . '<br>
                                     ' . $transaction->state_name . '<br>
                            Phone: ' . $transaction->phone . '
                          </div>';
    }

    $result .= '<div class="col-md-4">
                          <div class="container-box">
                            <input type="hidden" class="tid" value="' . $transaction->id . '">
                            <h3 style="margin-top: 0px;">#' . $transaction->transaction_no . '</h3>
                            <hr>
                            <div class="row">
                              <div class="col-6">
                                <p>' . $transaction->table_name . '</p>
                              </div>
                              <div class="col-6" align="right">
                                ' . $type . '
                              </div>
                            </div>
                            <hr>
                            <div class="list-group">';

    foreach ($details as $d) {
      $p_status = ($d->status == '1') ? 'preparing' : '';
      if (!empty($d->add_on_name)) {
        $desc = $d->add_on_name . '(x' . $d->ad_qty . ')';
      } else {
        $desc = $d->product_name . '(x' . $d->td_qty . ')';
      }

      if (!empty($d->add_on_name)) {
        if ($d->ta_status == '99') {
          $c_status = '<span class="badge bg-info">Pending</span>';
        } else {
          $c_status = '<span class="badge bg-success">On-going</span>';
        }
      } else {
        if ($d->td_status == '99') {
          $c_status = '<span class="badge bg-info">Pending</span>';
        } else {
          $c_status = '<span class="badge bg-success">On-going</span>';
        }
      }
      $result .= '<a href="#" class="list-group-item food-items ' . $p_status . '" data-filter="' . $d->id . '">
                            <div class="row">
                              <div class="col-6">
                                ' . $desc . '
                              </div>
                              <div class="col-6" align="right">
                                <div class="form-group">
                                  ' . $c_status . '
                                </div>
                              </div>
                            </div>';

      $add_on_details = TransactionAddon::select('t.variation_title', 'transaction_addons.*')
        ->join('product_variations AS v', 'v.id', 'transaction_addons.add_on_id')
        ->join('variation_titles as t', 't.id', 'v.variation_title_id')
        ->where('details_id', $d->id)
        ->get();
      if (empty($d->add_on_name)) {
        foreach ($add_on_details as $add_on) {
          $result .= '<p style="margin: 0px;">
                                <small style="font-size: 13px; font-weight: bold; color: red;">
                                  + ' . $add_on->add_on_title . ' - ' . $add_on->add_on_name . '
                                </small>
                              </p>';
        }
      }
      $result .= '<h6>Remark: </h6>
                          <br>
                          <span style="font-size: 13px; font-weight: bold; color: red;">
                            ' . $d->remark . '
                          </span>
                          </a>';
    }
    $result .= '</div>
                       <hr>
                       <button class="btn btn-warning select-all-btn">Select All</button>
                       <button class="btn btn-outline-warning deselect-all-btn">Deselect All</button>
                       <button class="btn btn-outline-success cook-btn">Cook</button>
                       <button class="btn btn-outline-primary ready-btn">Ready</button>
                  </div>
              </div>';

    if (count($details) <= 0) {
      $result = "";
    }

    return $result;
  }

  public function get_member_wallet(Request $request)
  {
    $member = User::where('phone', $request->phone)->first();
    if (!empty($member->id)) {

      $get_point_wallet = GlobalController::get_point_wallet($member->code);
      return array(
        'message' => 1,
        'wallet' => $get_point_wallet
      );
    } else {
      return array(
        'message' => 2,
        'wallet' => 0
      );
    }
  }

  public function GenerateQR(Request $request)
  {
    $qrcode = QrCode::where('table_id', $request->selected_table)->first();

    if (!empty($qrcode->id)) {
      return array($qrcode->id, $qrcode->table_id);
    } else {
      $input = [];
      $input['table_id'] = $request->selected_table;

      $qrcode = QrCode::create($input);

      return array($qrcode->id, $qrcode->table_id);
    }
  }

  public function getBackendSecondVariationList(Request $request)
  {
    // return 123;
    $variations = ProductSecondVariation::where('variation_id', $request->vid)
      ->orderBy('variation_name', 'asc')
      ->get();

    $result = "";
    foreach ($variations as $variation) {

      // $balance = GlobalController::second_variation_balance_quantity($variation->id);
      // $out_of_stock = ($balance <= 0) ? 'out-of-stock' : '';

      $result .= '<li>
                            <input type="hidden" class="variation_type" value="1">
                            <a href="#" class="second_variation_option" data-id="' . $variation->id . '">
                                ' . $variation->variation_name . '
                            </a>
                        </li>';
    }

    return $result;
  }

  public function refresh_carts(Request $request)
  {
    $member = !empty($request->member) ? $request->member : '';
    $sCart = Cart::where('cashier', '1')
      ->get();

    $result =  "";
    if (!$sCart->isEmpty()) {
      foreach ($sCart as $cart) {
        $get_pricing[$cart->id] = GlobalController::get_product_pricing(md5($cart->product_id), $member, $cart->sub_category_id, $cart->second_sub_category_id);

        $price = $get_pricing[$cart->id]['product_price'];
        $addons = "";
        if (!empty($cart->get_fv_det->id)) {
          $addons .= '<p>
                                    <small>
                                        <i class="bi bi-plus-square-fill" style="color: red;"></i> ' . $cart->get_product_det->variation_title . ' - ' . $cart->get_fv_det->variation_name . '
                                    </small>
                                </p>';
        }

        if (!empty($cart->get_sv_det->id)) {
          $addons .= '<p>
                                    <small>
                                        <i class="bi bi-plus-square-fill" style="color: red;"></i> ' . $cart->get_product_det->second_variation_title . ' - ' . $cart->get_sv_det->variation_name . '
                                    </small>
                                </p>';
        }

        if ($cart->get_product_det->variation_enable == 1) {
          if ($cart->get_product_det->second_variation_enable) {
            $stockBalance = GlobalController::second_variation_balance_quantity($cart->second_sub_category_id);
          } else {
            $stockBalance = GlobalController::variation_balance_quantity($cart->sub_category_id);
          }
        } else {
          $stockBalance = GlobalController::balance_quantity($cart->product_id);
        }
        $everyRemark = $cart->remark;
        $remarkArray = explode(',', $everyRemark);
        $remarkList = 'Remark: ' . $cart->remark . '<br>';
        // foreach ($remarkArray as $value) {
        //     $remarkList .='Remark: '.$value.'<br>';
        // }

        $result .= '<div class="row got-item">
                                <div class="col-3">
                                    <input type="hidden" name="cid" class="cid" value="' . md5($cart->id) . '">
                                    ' . $cart->get_product_det->product_name . '
                                    ' . $addons . '
                                </div>
                                <div class="col-3" align="right">
                                    RM ' . number_format(($price), 2) . '
                                </div>
                                <div class="col-3" align="right">
                                    <div class="form-group quantity-setting">
                                        <button class="btn btn-primary deduct-qty-button">
                                            <i class="bi bi-dash"></i>
                                        </button>
                                        <input type="text" class="form-control" name="quantity" value="' . $cart->qty . '" onkeypress="return isNumberKey(event)">
                                        <button class="btn btn-primary add-qty-button">
                                            <i class="bi bi-plus"></i>
                                        </button>
                                        <input type="hidden" name="balance_quantity" value="' . $stockBalance . '">
                                    </div>
                                </div>
                                <div class="col-3 " align="right">
                                    RM 
                                    <span class="row-total-price">
                                        ' . number_format(($price) * $cart->qty, 2) . '
                                    </span>
                                </div>
                                <div class="col-md-12" style="word-wrap: break-word;">'
          . $remarkList .
          '<hr>
                                </div>
                            </div>';
      }
    } else {
      $result .= '<div class="row">
                            <div class="col-12 no-item" align="center">
                                Please add an item
                            </div>
                        </div>';
    }

    return $result;
  }

  public function RefundTransaction(Request $request)
  {
    $transaction = Transaction::where('id', $request->tid)->first();

    $update = Transaction::where('id', $request->tid)->update(['status' => '95']);
    $cancel_commission = AffiliateCommission::where('transaction_no', $transaction->transaction_no)->update(['status' => '2']);

    return $request->tid;
  }

  public function SearchItems(Request $request)
  {
    $categories = Category::select('categories.*', 'categories.id AS c_id', 'i.image')
      ->join('category_images as i', 'categories.id', 'i.category_id')
      ->where('categories.status', '1')
      ->where('category_name', 'like', '%' . $request->search_value . '%');
    if (Auth::guard('merchant')->check()) {
      $categories = $categories->where('categories.merchant_id', Auth::user()->code);
    }
    $categories = $categories->get();

    $subCategory = SubCategory::select('sub_categories.sub_category_name AS content_name', 'sub_categories.id AS sc_id')
      ->where('sub_categories.status', '1')
      ->where('sub_category_name', 'like', '%' . $request->search_value . '%');
    if (Auth::guard('merchant')->check()) {
      $subCategory = $subCategory->where('sub_categories.merchant_id', Auth::user()->code);
    }
    $subCategory = $subCategory->get();

    $products = Product::select('i.image', 'products.*', 'products.id AS p_id')
      ->leftJoin(DB::raw('(
        SELECT product_id, image
        FROM product_images
        GROUP BY product_id
      ) as i'), 'i.product_id', '=', 'products.id')
      ->where('product_name', 'like', '%' . $request->search_value . '%');
    if (Auth::guard('merchant')->check()) {
      $products = $products->where('products.merchant_id', Auth::user()->code);
    }
    $products = $products->get();

    $categories = $categories->concat($subCategory)
      ->concat($products);

    $categories = array_values(Arr::sort($categories, function ($value) {
      return $value['c_id'];
    }));


    $result = "";
    foreach ($categories as $product) {
      if (!empty($product->p_id)) {
        $link_class = "product_items_option";
        $name = $product->product_name;
        $id = $product->p_id;
        $price = !empty($product->special_price) ? 'RM ' . number_format($product->special_price, 2) : 'RM ' . number_format($product->price, 2);
      } elseif (!empty($product->sc_id)) {
        $link_class = "sub_items_option";
        $name = $product->content_name;
        $id = $product->sc_id;
        $price = "";
      } elseif (!empty($product->c_id)) {
        $link_class = "items_option";
        $name = $product->category_name;
        $id = $product->c_id;
        $price = "";
      } else {
        $link_class = "";
        $name = "";
        $id = "";
        $price = "";
      }

      $image = !empty($product->image) ? $product->image : 'images/no-image-available-icon-6.jpg';


      $result .= '<div class="col-3">
                    <div class="form-group">
                        <a href="#" class="' . $link_class . '" data-filter="' . $product->sc_id . '" data-id="' . $product->p_id . '">
                            <div class="items-list" style="background-image: url(' . GlobalController::get_production_url($image) . ');">
                                <div class="items-content">
                                    <div class="product_name">
                                        <span>' . $name . '</span><br>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>          
                </div>';
    }

    return $result;
  }

  public function sendTransactionEinvoice(Request $request){
    $result = "0";
    $setting = SettingEinvoice::where('status', 1)->first();
    if($request->transaction_no && $setting){
      $eInvoice = new EinvoiceController($setting->client_id, $setting->client_secret);
      $callAPI = $eInvoice->submitDocument($request->transaction_no);

      if($callAPI['status'] == 'success'){
        $result = "1";
      }
    }

    return response()->json([
      'status' => $result,
      'message' => isset($callAPI['message']) ? $callAPI['message'] : ""
    ]);
  }

  public function DeleteSettingWebsiteMessage(Request $request)
  {
      $delete = SettingWebsiteMessage::find($request->sid)->delete();
  }

  public function uploadSecondBannerImage(Request $request)
  {
    $files = $request->file('file');
    $name = $files->getClientOriginalName();
    $exp = explode(".", $name);
    $file_ext = end($exp);
    $name = md5($name . date('Y-m-d H:i:s')) . '.' . $file_ext;

    $input = $request->all();

    $input['status'] = '1';
    $input['image'] = "uploads/second_banner/" . $name;

    if (Auth::guard('merchant')->check()) {
      $input['merchant_id'] = Auth::user()->code;
    }

    $files->move(GlobalController::get_image_path(("uploads/second_banner/")), $name);

    $product_image = SettingSecondBanner::create($input);


    $select = SettingSecondBanner::where('status', '1');
    if (Auth::guard('merchant')->check()) {
      $select = $select->where('merchant_id', Auth::user()->code);
    }
    $select = $select->get();


    $image_list = "";
    if (!$select->isEmpty()) {
      foreach ($select as $key => $value) {
        $image_list .= '<div class="product-image-thumbnail">
                                    <div class="form-group">
                                        <div class="delete-image-box">
                                            <a href="#" class="delete-image" data-id="' . $value->id . '">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </div>
                                        <div class="product-image-thumbnail-img" style="background-image: url(' . GlobalController::get_production_url($value->image) . ')"></div>
                                    </div>
                                </div>';
      }
    }

    return $image_list;
  }

  public function LoadSecondBannerImage()
  {
    $select = SettingSecondBanner::where('status', '1')->orderBy('sort_level', 'asc');

    if (Auth::guard('merchant')->check()) {
      $select = $select->where('merchant_id', Auth::user()->code);
    }
    $select = $select->get();

    $image_list = "";
    if (!$select->isEmpty()) {
      foreach ($select as $key => $value) {
        $image_list .= '<div class="product-image-thumbnail">
                                    <div class="form-group">
                                        <div class="delete-image-box">
                                            <a href="#" class="delete-image" data-id="' . $value->id . '">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </div>
                                        <div class="product-image-thumbnail-img" style="background-image: url(' . GlobalController::get_production_url($value->image) . ')"></div>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="second_banner_url" class="form-control second_banner_url" data-id="' . $value->id . '" placeholder="https://..." value="' . $value->url . '">
                                    </div>
                                </div>';
      }
    }

    return $image_list;
  }

  public function DeleteSecondBannerImage($id)
  {
    $delete = SettingSecondBanner::find($id);
    File::delete($delete->image);
    $delete = $delete->delete();
  }

  public function changeSecondBannerUrl(Request $request)
  {
    $banner = SettingSecondBanner::find($request->bid);
    $banner = $banner->update(['url' => $request->url]);
  }

  public function SortSecondBanner(Request $request)
  {
    $images = SettingSecondBanner::find($request->mid);
    $images = $images->update(['sort_level' => $request->number]);
  }

  public function QuizStatus(Request $request)
  {
      try{
          \DB::beginTransaction();

          $table = Quiz::find($request->row_id);
          $table->status = $request->status;
          $table->save();

          \DB::commit();
      }catch (\Exception $e){
          \DB::rollback();
          return Redirect::back()->withInput($request->all)->withErrors($e->getMessage());
      }catch(\Error $e){
          \DB::rollback();
          return Redirect::back()->withInput($request->all)->withErrors($e->getMessage());
      }
  }

  public function BlogStatus(Request $request)
  {
      $input = $request->all();
      $input['status'] = $request->status;
      $table = Blog::find($request->row_id);
      $table = $table->update($input);
  }

  public function FAQsStatus(Request $request)
  {
    try{
          \DB::beginTransaction();

          $table = Faq::find($request->row_id);
          $table->status = $request->status;
          $table->save();

          \DB::commit();
      }catch (\Exception $e){
          \DB::rollback();
          return Redirect::back()->withInput($request->all)->withErrors($e->getMessage());
      }catch(\Error $e){
          \DB::rollback();
          return Redirect::back()->withInput($request->all)->withErrors($e->getMessage());
      }
  }

  public function save_qr_type(Request $request){
      try{
        \DB::beginTransaction();
        // dd($request->all());
        for($a=0; $a<count($request->title); $a++){
          if(!empty($request->id[$a])){
              $setting = QrPayList::find($request->id[$a]);   
          }else{
              $setting = new QrPayList();
          }

          $setting->title = $request->title[$a];
        
          if(!empty($request->file('image')[$a])){
              $files = $request->file('image')[$a]; 
              $name = $files->getClientOriginalName();
              $exp = explode(".", $name);
              $file_ext = end($exp);
              $name = md5($name.date('Y-m-d H:i:s')).'.'.$file_ext;
          
              $files->move(GlobalController::get_image_path("uploads/qrpaylist/"), $name);
              $setting->image = "uploads/qrpaylist/".$name;
          }

          $setting->save();   
        }

        \DB::commit();
      } catch (\Exception $e){
          \DB::rollback();

          Toastr::error($e->getMessage());
          return Redirect::back()->withErrors($e->getMessage());
      }

      Toastr::success("Qr Paylist Update Successful");
      return redirect()->back();
  }

  public function DeleteQRType(Request $request)
  {
    $delete = QrPayList::find($request->id)->delete();
  }

  public function getTransactionSecondVariation(Request $request)
  {
      $product_second_variations = ProductSecondVariation::where('variation_id', $request->vid)->get();
      $product_variation = ProductVariation::find($request->vid);
      $product = Product::find($request->pid);
      $stockBalance = 0;

      $product_sv_list = "";

      if (!$product_second_variations->isEmpty() && $product->second_variation_enable == '1') {
          $product_sv_list .= '<label>Second Variation</label>
                                  <select class="form-control product_second_variation_option"  name="product_second_variation[]">
                                      <option value="">Select Second Variation</option>';

          foreach ($product_second_variations as $value) {
              $product_sv_list .= '<option value="' . $value->id . '" data-pid="' . $value->product_id . '" data-vid="' . $value->variation_id . '">
                                      ' . $value->variation_name . '
                                  </option>';
          }
          
          $product_sv_list .= '</select>';

          return array(1, $product_sv_list);
      } else {
          return array(2, $stockBalance);
      }
  }

  public function get_remaining_points(Request $request){
    $GetPVWallet = GlobalController::get_point_wallet($request->agent);

    return $GetPVWallet;
  }
}
