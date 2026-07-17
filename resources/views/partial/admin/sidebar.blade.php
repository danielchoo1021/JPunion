@php
	$permission_level = (!empty(Auth::guard($data['userGuardRole'])->user()->permission_lvl)) ? Auth::guard($data['userGuardRole'])->user()->permission_lvl : '1';
@endphp
@if(Request::segment(1) != 'transaction_invoice' && 
	Request::segment(1) != 'print_withdrawal_list' && 
	Request::segment(1) != 'print_sales_report' && 
	Request::segment(1) != 'print_order_report' && 
    Request::segment(1) != 'print_point_order_report' &&
    Request::segment(1) != 'print_commission_report' && 
    Request::segment(1) != 'print_sales_report_details' && 
    Request::segment(1) != 'cashier_screen' && 
    Request::segment(1) != 'topup_invoice' &&
    Request::segment(1) != 'print_agent_sales_report_detail')
<div id="sidebar">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header position-relative">
            <div class="d-flex justify-content-between align-items-center">
              	<div class="logo">
                	<a href="{{ route('dashboard.dashboards.index') }}"
                        style="font-size: 17px;">
                        <img src="{{ asset($data['website_logo']) }}" alt="Logo" srcset="" style="height: auto; width: 20%;" />
                       {{ $data['website_name'] }}
                   </a>
              	</div>
              	<div class="sidebar-toggler x">
                	<a href="#" class="sidebar-hide d-xl-none d-block">
                  		<i class="bi bi-x bi-middle"></i>
                  	</a>
              	</div>
            </div>
        </div>
        <div class="sidebar-menu">
            <ul class="menu">
                <li class="sidebar-title">
                    <!-- <div class="theme-toggle d-flex gap-2 align-items-center mt-2">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink"
                            aria-hidden="true"
                            role="img"
                            class="iconify iconify--system-uicons"
                            width="20"
                            height="20"
                            preserveAspectRatio="xMidYMid meet"
                            viewBox="0 0 21 21">
                            <g
                                fill="none"
                                fill-rule="evenodd"
                                stroke="currentColor"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <path
                                  d="M10.5 14.5c2.219 0 4-1.763 4-3.982a4.003 4.003 0 0 0-4-4.018c-2.219 0-4 1.781-4 4c0 2.219 1.781 4 4 4zM4.136 4.136L5.55 5.55m9.9 9.9l1.414 1.414M1.5 10.5h2m14 0h2M4.135 16.863L5.55 15.45m9.899-9.9l1.414-1.415M10.5 19.5v-2m0-14v-2"
                                  opacity=".3"
                                ></path>
                                <g transform="translate(-210 -1)">
                                    <path d="M220.5 2.5v2m6.5.5l-1.5 1.5"></path>
                                    <circle cx="220.5" cy="11.5" r="4"></circle>
                                    <path
                                        d="m214 5l1.5 1.5m5 14v-2m6.5-.5l-1.5-1.5M214 18l1.5-1.5m-4-5h2m14 0h2"
                                    ></path>
                                </g>
                            </g>
                        </svg>
                        <div class="form-check form-switch fs-6">
                            <input
                                class="form-check-input me-0"
                                type="checkbox"
                                id="toggle-dark"
                                style="cursor: pointer"
                            />
                            <label class="form-check-label"></label>
                        </div>
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink"
                            aria-hidden="true"
                            role="img"
                            class="iconify iconify--mdi"
                            width="20"
                            height="20"
                            preserveAspectRatio="xMidYMid meet"
                            viewBox="0 0 24 24"
                        >
                            <path
                                fill="currentColor"
                                d="m17.75 4.09l-2.53 1.94l.91 3.06l-2.63-1.81l-2.63 1.81l.91-3.06l-2.53-1.94L12.44 4l1.06-3l1.06 3l3.19.09m3.5 6.91l-1.64 1.25l.59 1.98l-1.7-1.17l-1.7 1.17l.59-1.98L15.75 11l2.06-.05L18.5 9l.69 1.95l2.06.05m-2.28 4.95c.83-.08 1.72 1.1 1.19 1.85c-.32.45-.66.87-1.08 1.27C15.17 23 8.84 23 4.94 19.07c-3.91-3.9-3.91-10.24 0-14.14c.4-.4.82-.76 1.27-1.08c.75-.53 1.93.36 1.85 1.19c-.27 2.86.69 5.83 2.89 8.02a9.96 9.96 0 0 0 8.02 2.89m-1.64 2.02a12.08 12.08 0 0 1-7.8-3.47c-2.17-2.19-3.33-5-3.49-7.82c-2.81 3.14-2.7 7.96.31 10.98c3.02 3.01 7.84 3.12 10.98.31Z"
                            >
                            </path>
                        </svg>
                    </div> -->
                </li>
                <div class="" align="left" style=" padding: 0px 0px; margin-top: -20px;">
                    <select class="backend_global_language" name="backend_global_language" style="padding: 0.375rem 0.75rem; width: auto; -webkit-appearance: auto; z-index: 1000; border: 1px solid" onchange="changeBackendLanguage(value);">
                        <option>{{ isset($data['backendlang']['backendlang']['language']) ? $data['backendlang']['backendlang']['language'] :'语言'}}</option>
                        <option value="1">{{ isset($data['backendlang']['backendlang']['chinese']) ? $data['backendlang']['backendlang']['chinese'] :'中文'}}</option>
                        <option value="2">{{ isset($data['backendlang']['backendlang']['english']) ? $data['backendlang']['backendlang']['english'] :'英文'}}</option>
                    </select>
                </div>

                @if($data['website_setting']->authorise_enable == 1)
                <li class="sidebar-title">
                    <a href="https://newseller.vesson.my?vm={{ !empty($_COOKIE['vmerchant']) ? $_COOKIE['vmerchant'] : '' }}" class="switch-frontend sidebar-link" target="_blank">
                        <i class="bi bi-link"></i> <span>Switch Frontend</span>
                    </a>
                </li>
                @endif
              	<li class="sidebar-title">{{ isset($data['backendlang']['backendlang']['menu']) ? $data['backendlang']['backendlang']['menu'] :'' }}</li>
                @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['dashboard']))
              	<li class="sidebar-item {{ (Request::segment(1) == 'dashboards') ? 'active' : '' }}">
                	<a href="{{ route('dashboard.dashboards.index') }}" class="sidebar-link">
                  		<i class="bi bi-grid-fill"></i>
                  		<span> {{ isset($data['backendlang']['backendlang']['dashboard']) ? $data['backendlang']['backendlang']['dashboard'] :'' }}</span>
                	</a>
              	</li>
                @endif

                @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['company-profile']))
                <li class="sidebar-item {{ (Request::segment(1) == 'admins') ? 'active' : '' }}">
                    <a href="{{ route('admin.admins.index') }}" class="sidebar-link">
                        <i class="bi bi-buildings-fill"></i>
                        <span>  {{ isset($data['backendlang']['backendlang']['Company_Profile']) ? $data['backendlang']['backendlang']['Company_Profile'] :'' }}</span>
                    </a>
                </li>
                @endif

                @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['cashier-screen']))
                <li class="sidebar-item {{ (Request::segment(1) == 'cashier_screen') ? 'active' : '' }}">
                    <a href="{{ route('cashier_screen') }}" class="sidebar-link">
                        <i class="bi bi-cash"></i>
                        <span>  {{ isset($data['backendlang']['backendlang']['POS']) ? $data['backendlang']['backendlang']['POS'] : ''}}</span>
                    </a>
                </li>
                @endif

                @if(Auth::guard('admin')->check())
                    <li class="sidebar-item {{ (Request::segment(1) == 'user_permissions') ? 'active' : '' }}">
                        <a href="{{ route('user_permission.user_permissions.index') }}" class="sidebar-link">
                            <i class="bi bi-person-fill-gear"></i>
                            <span>  {{ isset($data['backendlang']['backendlang']['permission']) ? $data['backendlang']['backendlang']['permission'] :'' }}</span>
                        </a>
                    </li>
                @endif

                @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['agent-list']) || 
                    !empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['agent-wallet-list']) || 
                    !empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['agent-pending-list']) || 
                    !empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['member-list']) || 
                    !empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['merchant-list']) || 
                    !empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['merchant-pending-list']))
                <li class="sidebar-item has-sub
                           {{ (Request::segment(1) == 'agents' ||
                               Request::segment(1) == 'members' ||
                               Request::segment(1) == 'pending_agent' ||
                               Request::segment(1) == 'tree' ||
                               Request::segment(1) == 'agent_wallet' ||
                               Request::segment(1) == 'merchants' ||
                               Request::segment(1) == 'pending_merchant' ||
                               Request::segment(1) == 'pending_member' ||
                               Request::segment(1) == 'member_wallet') ? 'active' : '' }}">
                    <a href="#" class="sidebar-link">
                        <i class="bi bi-people-fill"></i>
                        <span>  {{ isset($data['backendlang']['backendlang']['userManage']) ? $data['backendlang']['backendlang']['userManage'] : ''  }}</span>
                        @if($data['total_pending'] > 0 &&
                            !empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['agent-pending-list']))
                        <span class="badge bg-danger">
                            {{ $data['total_pending'] }}
                        </span>
                        @endif
                    </a>

                    <ul class="submenu">

                        @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['agent-list']) || 
                            !empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['agent-wallet-list']) || 
                            !empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['agent-pending-list']))
                        <li class="submenu-item has-sub
                                   {{ (Request::segment(1) == 'agents' ||
                                       Request::segment(1) == 'pending_agent' ||
                                       Request::segment(1) == 'tree' ||
                                       Request::segment(1) == 'agent_wallet') ? 'active' : '' }}">
                            <a href="#" class="submenu-link">
                                {{ isset($data['backendlang']['backendlang']['Agent']) ? $data['backendlang']['backendlang']['Agent'] : '' }}
                            </a>

                            <ul class="submenu submenu-level-2">
                                @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['agent-list']))
                                <li class="submenu-item {{ (Request::segment(1) == 'agents' || Request::segment(1) == 'tree') ? 'active' : '' }}">
                                    <a href="{{ route('agent.agents.index') }}" class="submenu-link">
                                        {{ isset($data['backendlang']['backendlang']['Agent_List']) ? $data['backendlang']['backendlang']['Agent_List'] : '' }}
                                    </a>
                                </li>
                                @endif
                                @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['agent-wallet-list']))
                                <li class="submenu-item {{ (Request::segment(1) == 'agent_wallet') ? 'active' : '' }}">
                                    <a href="{{ route('agent_wallet') }}" class="submenu-link">
                                        {{ isset($data['backendlang']['backendlang']['agentWallet']) ? $data['backendlang']['backendlang']['agentWallet'] : '' }}
                                    </a>
                                </li>
                                @endif
                                @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['agent-pending-list']))
                                <li class="submenu-item {{ (Request::segment(1) == 'pending_agent') ? 'active' : '' }}">
                                    <a href="{{ route('pending_agent') }}" class="submenu-link">
                                         {{ isset($data['backendlang']['backendlang']['Pending']) ? $data['backendlang']['backendlang']['Pending'] : '' }}
                                        @if($data['total_pending'] > 0 &&
                                            !empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['agent-pending-list']))
                                        <span class="badge bg-danger">
                                            {{ $data['total_pending'] }}
                                        </span>
                                        @endif
                                    </a>
                                </li>
                                @endif
                            </ul>
                        </li>
                        @endif
                        @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['member-list']))
                        <li class="submenu-item has-sub
                                   {{ (Request::segment(1) == 'members' ||
                                       Request::segment(1) == 'pending_member' ||
                                       Request::segment(1) == 'tree' ||
                                       Request::segment(1) == 'member_wallet') ? 'active' : '' }}">
                            <a href="#" class="submenu-link">
                                {{ isset($data['backendlang']['backendlang']['members']) ? $data['backendlang']['backendlang']['members'] : '' }}
                            </a>
                            
                            <ul class="submenu submenu-level-2">
                                @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['member-list']))
                                <li class="submenu-item {{ (Request::segment(1) == 'members' || Request::segment(1) == 'tree') ? 'active' : '' }}">
                                    <a href="{{ route('member.members.index') }}" class="submenu-link">
                                        {{ isset($data['backendlang']['backendlang']['Member_List']) ? $data['backendlang']['backendlang']['Member_List'] : '' }}
                                    </a>
                                </li>
                                @endif
                                @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['member-wallet-list']))
                                <li class="submenu-item {{ (Request::segment(1) == 'member_wallet') ? 'active' : '' }}">
                                    <a href="{{ route('member_wallet') }}" class="submenu-link">
                                        {{ isset($data['backendlang']['backendlang']['memberWallet']) ? $data['backendlang']['backendlang']['memberWallet'] : '' }}
                                    </a>
                                </li>
                                @endif
                                @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['member-pending-list']))
                                <li class="submenu-item {{ (Request::segment(1) == 'pending_member') ? 'active' : '' }}">
                                    <a href="{{ route('pending_member') }}" class="submenu-link">
                                        {{ isset($data['backendlang']['backendlang']['Pending']) ? $data['backendlang']['backendlang']['Pending'] : '' }}
                                        @if($data['total_pending'] > 0 &&
                                            !empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['member-pending-list']))
                                        <span class="badge bg-danger">
                                            {{ $data['total_pending'] }}
                                        </span>
                                        @endif
                                    </a>
                                </li>
                                @endif
                            </ul>
                        </li>
                        @endif

                        @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['merchant-list']) || 
                            !empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['merchant-pending-list']))
                        <li class="submenu-item has-sub
                                   {{ (Request::segment(1) == 'merchants' ||
                                       Request::segment(1) == 'pending_merchant') ? 'active' : '' }}">
                            <a href="#" class="submenu-link">
                                 {{ isset($data['backendlang']['backendlang']['merchants']) ? $data['backendlang']['backendlang']['merchants'] : '' }}
                            </a>

                            <ul class="submenu submenu-level-2">
                                @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['merchant-list']))
                                <li class="submenu-item {{ (Request::segment(1) == 'merchants' || Request::segment(1) == 'tree') ? 'active' : '' }}">
                                    <a href="{{ route('merchant.merchants.index') }}" class="submenu-link">
                                         {{ isset($data['backendlang']['backendlang']['merchantList']) ? $data['backendlang']['backendlang']['merchantList'] : '' }}
                                    </a>
                                </li>
                                @endif
                                @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['merchant-pending-list']))
                                <li class="submenu-item {{ (Request::segment(1) == 'pending_merchant') ? 'active' : '' }}">
                                    <a href="{{ route('pending_merchant') }}" class="submenu-link">
                                        {{ isset($data['backendlang']['backendlang']['Pending']) ? $data['backendlang']['backendlang']['Pending'] : '' }}
                                        @if($data['total_merchant_pending'] > 0 &&
                                            !empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['merchant-pending-list']))
                                        <span class="badge bg-danger">
                                            {{ $data['total_merchant_pending'] }}
                                        </span>
                                        @endif
                                    </a>
                                </li>
                                @endif
                            </ul>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif


                @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['product-list']) || 
                    !empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['package-list']))
                  	<li class="sidebar-item has-sub 
                               {{ (Request::segment(1) == 'products' ||
                                   Request::segment(2) == 'packages_list') ? 'active' : '' }}">
                    	<a href="#" class="sidebar-link">
                      		<i class="bi bi-boxes"></i>
                      		<span>  {{ isset($data['backendlang']['backendlang']['productsManage']) ? $data['backendlang']['backendlang']['productsManage'] : ''  }}</span>
                    	</a>

                    	<ul class="submenu">
                            @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['product-list']))
                            <li class="submenu-item {{ (Request::segment(1) == 'products' && 
                                                        Request::segment(2) != 'packages_list' && 
                                                        Request::segment(2) != 'packages') ? 'active' : '' }}">
                                <a href="{{ route('product.products.index') }}" class="submenu-link">
                                    {{ isset($data['backendlang']['backendlang']['products']) ? $data['backendlang']['backendlang']['products'] : ''  }}
                                </a>
                            </li>
                            @endif

                            @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['package-list']))
                            <li class="submenu-item {{ (Request::segment(2) == 'packages_list' ||
                                                        Request::segment(2) == 'packages') ? 'active' : '' }}">
                                <a href="{{ route('packages_list') }}" class="submenu-link">
                                    {{ isset($data['backendlang']['backendlang']['packages']) ? $data['backendlang']['backendlang']['packages'] : ''  }}
                                </a>
                            </li>
                            @endif
                		</ul>
                  	</li>
                @endif

                @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['category-list']) || 
                    !empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['sub-category-list']) || 
                    !empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['brand-list']) || 
                    !empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['setting-uom-list']))
                    <li class="sidebar-item has-sub 
                               {{ (Request::segment(1) == 'categories' ||
                                   Request::segment(1) == 'sub_categories' ||
                                   Request::segment(1) == 'brands' ||
                                   Request::segment(1) == 'setting_uom') ? 'active' : '' }}">

                        <a href="#" class="sidebar-link">
                            <i class="bi bi-ui-radios-grid"></i>
                            <span>{{ isset($data['backendlang']['backendlang']['unitControl']) ? $data['backendlang']['backendlang']['unitControl'] : ''  }}</span>
                        </a>
                        <ul class="submenu">
                            @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['category-list']) || 
                                !empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['sub-category-list']))
                            <li class="submenu-item has-sub
                                       {{ (Request::segment(1) == 'categories' ||
                                           Request::segment(1) == 'sub_categories') ? 'active' : '' }}">
                                <a href="#" class="submenu-link">
                                    {{ isset($data['backendlang']['backendlang']['categories']) ? $data['backendlang']['backendlang']['categories'] : ''  }}
                                </a>

                                <ul class="submenu submenu-level-2">
                                    @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['category-list']))
                                    <li class="submenu-item {{ (Request::segment(1) == 'categories') ? 'active' : '' }}">
                                        <a href="{{ route('category.categories.index') }}" class="submenu-link">
                                            {{ isset($data['backendlang']['backendlang']['Category']) ? $data['backendlang']['backendlang']['Category'] : ''  }}
                                        </a>
                                    </li>
                                    @endif

                                    @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['sub-category-list']))
                                    <li class="submenu-item {{ (Request::segment(1) == 'sub_categories') ? 'active' : '' }}">
                                        <a href="{{ route('sub_category.sub_categories.index') }}" class="submenu-link">
                                             {{ isset($data['backendlang']['backendlang']['subCategory']) ? $data['backendlang']['backendlang']['subCategory'] : ''  }}
                                        </a>
                                    </li>
                                    @endif
                                </ul>
                            </li>
                            @endif
                            @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['brand-list']))
                            <li class="submenu-item {{ (Request::segment(1) == 'brands') ? 'active' : '' }}">
                                <a href="{{ route('brand.brands.index') }}" class="submenu-link">
                                    {{ isset($data['backendlang']['backendlang']['Brand']) ? $data['backendlang']['backendlang']['Brand'] : ''  }}
                                </a>
                            </li>
                            @endif

                            @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['setting-uom-list']))
                            <li class="submenu-item {{ (Request::segment(1) == 'setting_uom') ? 'active' : '' }}">
                                <a href="{{ route('setting_uom') }}" class="submenu-link">
                                    {{ isset($data['backendlang']['backendlang']['Setting_UOM']) ? $data['backendlang']['backendlang']['Setting_UOM'] : ''  }}
                                </a>
                            </li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['voucher-list']) || 
                    !empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['flash-sale-list']) || 
                    !empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['add-on-deal-list']) || 
                    !empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['cart-links-list']))
                    <li class="sidebar-item has-sub
                               {{ (Request::segment(1) == 'promotions' ||
                                   Request::segment(1) == 'add_on_deal' || 
                                   Request::segment(1) == 'add_on_deal_create' || 
                                   Request::segment(1) == 'add_on_deal_edit' ||
                                   Request::segment(1) == 'flash_sales' ||
                                   Request::segment(1) == 'cart_links') ? 'active' : '' }}">
                        <a href="#" class="sidebar-link">
                            <i class="bi bi-tags"></i>
                            <span>{{ isset($data['backendlang']['backendlang']['marketingCentre']) ? $data['backendlang']['backendlang']['marketingCentre'] : ''  }}</span>
                        </a>

                        <ul class="submenu">
                            @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['voucher-list']))
                            <li class="submenu-item {{ (Request::segment(1) == 'promotions' && 
                                                        (Request::segment(2) == 'create' || Request::segment(3) == 'edit' || Request::segment(2) == '')) ? 'active' : '' }}">
                                <a href="{{ route('promotion.promotions.index') }}" class="submenu-link">
                                     {{ isset($data['backendlang']['backendlang']['voucher_list']) ? $data['backendlang']['backendlang']['voucher_list'] : ''  }}
                                </a>
                            </li>
                            @endif

                            @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['flash-sale-list']))
                            <li class="submenu-item {{ (Request::segment(1) == 'flash_sales') ? 'active' : '' }}">
                                <a href="{{ route('flash_sale.flash_sales.index') }}" class="submenu-link">
                                    {{ isset($data['backendlang']['backendlang']['flash_sale']) ? $data['backendlang']['backendlang']['flash_sale'] : ''  }}
                                </a>
                            </li>
                            @Endif

                            @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['add-on-deal-list']))
                            <li class="submenu-item {{ (Request::segment(1) == 'add_on_deal' || Request::segment(1) == 'add_on_deal_create' || Request::segment(1) == 'add_on_deal_edit') ? 'active' : '' }}">
                                <a href="{{ route('add_on_deal') }}" class="submenu-link">
                                    {{ isset($data['backendlang']['backendlang']['add_on_deal']) ? $data['backendlang']['backendlang']['add_on_deal'] : ''  }}
                                </a>
                            </li>
                            @endif

                            @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['cart-links-list']))
                            <li class="submenu-item {{ (Request::segment(1) == 'cart_links') ? 'active' : '' }}">
                                <a href="{{ route('cart_link.cart_links.index') }}" class="submenu-link">
                                    {{ isset($data['backendlang']['backendlang']['cart_link']) ? $data['backendlang']['backendlang']['cart_link'] : ''  }}
                                </a>
                            </li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['transaction-list']) || 
                    !empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['point-transaction-list']) || 
                    !empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['withdrawal-list']) || 
                    !empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['topup-list']))
                <li class="sidebar-item has-sub
                           {{ (Request::segment(1) == 'transactions' ||
                               Request::segment(1) == 'withdrawal_list' ||
                               Request::segment(1) == 'topup_list') ? 'active' : '' }}">
                    <a href="#" class="sidebar-link">
                        <i class="bi bi-stack"></i>
                        <span>{{ isset($data['backendlang']['backendlang']['orders_manage']) ? $data['backendlang']['backendlang']['orders_manage'] : ''  }}</span>
                        @if($data['totalPendingTrans'] > 0)
                        <span class="badge bg-danger">
                            {{ $data['totalPendingTrans'] }}
                        </span>
                        @endif
                    </a>

                    <ul class="submenu">
                        @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['transaction-list']))
                        <li class="submenu-item {{ (Request::segment(1) == 'transactions' && empty(request('mall'))) ? 'active' : '' }}">
                            <a href="{{ route('transaction.transactions.index') }}" class="submenu-link">
                                {{ isset($data['backendlang']['backendlang']['Transaction']) ? $data['backendlang']['backendlang']['Transaction'] : ''  }}
                                @if($data['allPendingTrans'] > 0)
                                <span class="badge bg-danger">
                                    {{ $data['allPendingTrans'] }}
                                </span>
                                @endif
                            </a>
                        </li>
                        @endif

                        @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['point-transaction-list']))
                        <li class="submenu-item {{ (Request::segment(1) == 'transactions' && !empty(request('mall'))) || Request::segment(2) == 'create_point' ? 'active' : '' }}">
                            <a href="{{ route('transaction.transactions.index', 'mall='.'1') }}" class="submenu-link">
                               {{ isset($data['backendlang']['backendlang']['point_transaction']) ? $data['backendlang']['backendlang']['point_transaction'] : ''  }}
                            </a>
                        </li>
                        @endif

                        @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['topup-list']))
                        <li class="submenu-item {{ (Request::segment(1) == 'topup_list') ? 'active' : '' }}">
                            <a href="{{ route('topup_list') }}" class="submenu-link">
                                {{ isset($data['backendlang']['backendlang']['topup_list']) ? $data['backendlang']['backendlang']['topup_list'] : ''  }}
                                @if($data['allPendingTopup'] > 0)
                                <span class="badge bg-danger">
                                    {{ $data['allPendingTopup'] }}
                                </span>
                                @endif
                            </a>
                        </li>
                        @endif

                        @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['withdrawal-list']))
                        <li class="submenu-item {{ (Request::segment(1) == 'withdrawal_list') ? 'active' : '' }}">
                            <a href="{{ route('withdrawal_list') }}" class="submenu-link">
                                {{ isset($data['backendlang']['backendlang']['Withdrawal_List']) ? $data['backendlang']['backendlang']['Withdrawal_List'] : ''  }}
                                @if($data['allPendingWith'] > 0)
                                <span class="badge bg-danger">
                                    {{ $data['allPendingWith'] }}
                                </span>
                                @endif
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif

                @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['sales-report']) || 
                    !empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['order-report']) || 
                    !empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['point-order-report']) || 
                    !empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['commission-report']) || 
                    !empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['stock-report']) || 
                    !empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['agent-report']))
                    <li class="sidebar-item has-sub
                               {{ (Request::segment(1) == 'agent_stock_report' ||
                                   Request::segment(1) == 'sales_report' ||
                                   Request::segment(1) == 'order_report' ||
                                   Request::segment(1) == 'point_order_report' ||
                                   Request::segment(1) == 'commission_report' ||
                                   Request::segment(1) == 'team_reward_report' ||
                                   Request::segment(1) == 'topup_wallet_report' ||
                                   Request::segment(1) == 'cash_wallet_report' ||
                                   Request::segment(1) == 'stock_report' ||
                                   Request::segment(1) == 'stock_report_details' ||
                                   Request::segment(1) == 'agent_sales_report' ||
                                   Request::segment(1) == 'agent_sales_report_detail') ? 'active' : '' }}">
                        <a href="#" class="sidebar-link">
                            <i class="bi bi-clipboard-data"></i>
                            <span> {{ isset($data['backendlang']['backendlang']['report_manage']) ? $data['backendlang']['backendlang']['report_manage'] : ''  }}</span>
                        </a>

                        <ul class="submenu">
                            @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['sales-report']))
                            <li class="submenu-item {{ (Request::segment(1) == 'sales_report') ? 'active' : '' }}">
                                <a href="{{ route('sales_report') }}" class="submenu-link">
                                    {{ isset($data['backendlang']['backendlang']['item_profit_report']) ? $data['backendlang']['backendlang']['item_profit_report'] : ''  }}
                                </a>
                            </li>
                            @endif

                            @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['order-report']))
                            <li class="submenu-item {{ (Request::segment(1) == 'order_report') ? 'active' : '' }}">
                                <a href="{{ route('order_report') }}" class="submenu-link">
                                    {{ isset($data['backendlang']['backendlang']['order_report']) ? $data['backendlang']['backendlang']['order_report'] : ''  }}
                                </a>
                            </li>
                            @endif
                            
                            @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['point-order-report']))
                            <li class="submenu-item {{ (Request::segment(1) == 'point_order_report') ? 'active' : '' }}">
                                <a href="{{ route('point_order_report') }}" class="submenu-link">
                                    {{ isset($data['backendlang']['backendlang']['point_order_report']) ? $data['backendlang']['backendlang']['point_order_report'] : ''  }}
                                </a>
                            </li>
                            @endif

                            @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['commission-report']))
                            <li class="submenu-item {{ (Request::segment(1) == 'commission_report') ? 'active' : '' }}">
                                <a href="{{ route('commission_report') }}" class="submenu-link">
                                      {{ isset($data['backendlang']['backendlang']['Commission_Report']) ? $data['backendlang']['backendlang']['Commission_Report'] : ''  }}
                                </a>
                            </li>

                            <li class="submenu-item {{ (Request::segment(1) == 'team_reward_report') ? 'active' : '' }}">
                                <a href="{{ route('team_reward_report') }}" class="submenu-link">
                                      {{ isset($data['backendlang']['backendlang']['Team_Reward_Report']) ? $data['backendlang']['backendlang']['Team_Reward_Report'] : ''  }}
                                </a>
                            </li>
                            @endif

                            <li class="submenu-item {{ (Request::segment(1) == 'topup_wallet_report') ? 'active' : '' }}">
                                <a href="{{ route('topup_wallet_report') }}" class="submenu-link">
                                      {{ isset($data['backendlang']['backendlang']['topup_wallet_report']) ? $data['backendlang']['backendlang']['topup_wallet_report'] : ''  }}
                                </a>
                            </li>

                            <li class="submenu-item {{ (Request::segment(1) == 'cash_wallet_report') ? 'active' : '' }}">
                                <a href="{{ route('cash_wallet_report') }}" class="submenu-link">
                                     {{ isset($data['backendlang']['backendlang']['cash_wallet_report']) ? $data['backendlang']['backendlang']['cash_wallet_report'] : ''  }}
                                </a>
                            </li>

                            @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['stock-report']))
                            <li class="submenu-item {{ (Request::segment(1) == 'stock_report' || Request::segment(1) == 'stock_report_details') ? 'active' : '' }}">
                                <a href="{{ route('stock_report') }}" class="submenu-link">
                                     {{ isset($data['backendlang']['backendlang']['stock_report']) ? $data['backendlang']['backendlang']['stock_report'] : ''  }}
                                </a>
                            </li>
                            @endif

                            @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['agent-report']))
                            <li class="submenu-item {{ (Request::segment(1) == 'agent_sales_report' || Request::segment(1) == 'agent_sales_report_detail') ? 'active' : '' }}">
                                <a href="{{ route('agent_sales_report') }}" class="submenu-link">
                                     {{ isset($data['backendlang']['backendlang']['agent_report']) ? $data['backendlang']['backendlang']['agent_report'] : ''  }}
                                </a>
                            </li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['agent-lvl-list']) || 
                    !empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['agent-order-rebate-list']) || 
                    !empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['hierarchy-bonus-list']) || 
                    !empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['referral-reward-list']) || 
                    !empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['performance-reward-list']) || 
                    !empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['team-reward-list']) || 
                    !empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['prize-pool-list']) || 
                    !empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['topup-bonus-list']))
                    <li class="sidebar-item has-sub
                               {{ (Request::segment(1) == 'setting_agent_level' ||
                                   Request::segment(1) == 'setting_agent_rebate' ||
                                   Request::segment(1) == 'setting_merchant_commission' ||
                                   Request::segment(1) == 'setting_recommend_bonus' ||
                                   Request::segment(1) == 'setting_performance_dividend' ||
                                   Request::segment(1) == 'setting_team_dividend' ||
                                   Request::segment(1) == 'setting_prize_pool' ||
                                   Request::segment(1) == 'setting_topup_amount') ? 'active' : '' }}">
                        <a href="#" class="sidebar-link">
                            <i class="bi bi-coin"></i>
                            <span> {{ isset($data['backendlang']['backendlang']['Bonus_Manage']) ? $data['backendlang']['backendlang']['Bonus_Manage'] : ''  }}
                                </span>
                        </a>

                        <ul class="submenu">
                            @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['agent-lvl-list']))
                            <li class="submenu-item {{ (Request::segment(1) == 'setting_agent_level') ? 'active' : '' }}">
                                <a href="{{ route('setting_agent_level') }}" class="submenu-link">
                                    {{ isset($data['backendlang']['backendlang']['Agent_Level']) ? $data['backendlang']['backendlang']['Agent_Level'] : ''  }}
                                </a>
                            </li>
                            @endif

                            @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['agent-order-rebate-list']))
                            <li class="submenu-item {{ (Request::segment(1) == 'setting_agent_rebate') ? 'active' : '' }}">
                                <a href="{{ route('setting_agent_rebate') }}" class="submenu-link">
                                    {{ isset($data['backendlang']['backendlang']['Order_Rebate']) ? $data['backendlang']['backendlang']['Order_Rebate'] : ''  }}
                                </a>
                            </li>
                            @endif

                            @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['hierarchy-bonus-list']))
                            <li class="submenu-item {{ (Request::segment(1) == 'setting_merchant_commission') ? 'active' : '' }}">
                                <a href="{{ route('setting_merchant_commission') }}" class="submenu-link">
                                    {{ isset($data['backendlang']['backendlang']['Hierarchy_Bonus']) ? $data['backendlang']['backendlang']['Hierarchy_Bonus'] : ''  }}
                                </a>
                            </li>
                            @endif

                            @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['referral-reward-list']))
                            <li class="submenu-item {{ (Request::segment(1) == 'setting_recommend_bonus') ? 'active' : '' }}">
                                <a href="{{ route('setting_recommend_bonus') }}" class="submenu-link">
                                   {{ isset($data['backendlang']['backendlang']['Referral_Reward']) ? $data['backendlang']['backendlang']['Referral_Reward'] : ''  }}
                                </a>
                            </li>
                            @endif

                            @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['topup-bonus-list']))
                            <li class="submenu-item {{ (Request::segment(1) == 'setting_topup_amount') ? 'active' : '' }}">
                                <a href="{{ route('setting_topup_amount') }}" class="submenu-link">
                                    {{ isset($data['backendlang']['backendlang']['Topup_Bonus']) ? $data['backendlang']['backendlang']['Topup_Bonus'] : ''  }}
                                </a>
                            </li>
                            @endif

                            @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['performance-reward-list']))
                            <li class="submenu-item {{ (Request::segment(1) == 'setting_performance_dividend') ? 'active' : '' }}">
                                <a href="{{ route('setting_performance_dividend') }}" class="submenu-link">
                                    {{ isset($data['backendlang']['backendlang']['Performance_Reward']) ? $data['backendlang']['backendlang']['Performance_Reward'] : ''  }}
                                </a>
                            </li>
                            @endif

                            @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['team-reward-list']))
                            <li class="submenu-item {{ (Request::segment(1) == 'setting_team_dividend') ? 'active' : '' }}">
                                <a href="{{ route('setting_team_dividend') }}" class="submenu-link">
                                    {{ isset($data['backendlang']['backendlang']['Team_Reward']) ? $data['backendlang']['backendlang']['Team_Reward'] : ''  }}
                                </a>
                            </li>
                            @endif

                            @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['prize-pool-list']))
                            <li class="submenu-item {{ (Request::segment(1) == 'setting_prize_pool') ? 'active' : '' }}">
                                <a href="{{ route('setting_prize_pool') }}" class="submenu-link">
                                    {{ isset($data['backendlang']['backendlang']['Prize_Pool']) ? $data['backendlang']['backendlang']['Prize_Pool'] : ''  }}
                                </a>
                            </li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['setting-banner-list']) || 
                    !empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['setting-shipping-fee-list']) || 
                    !empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['easyparcel-pickup-list']) || 
                    !empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['pickup-list']) || 
                    !empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['setting-home-page-list']) || 
                    !empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['website-setting-list']) || 
                    !empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['setting-website-message-list']) || 
                    !empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['setting-header-image']) || 
                    !empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['setting-second-banner']) || 
                    !empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['setting-home-videos']) || 
                    !empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['setting-home-overview']) || 
                    !empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['setting-website-countries']) ||
                    !empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['setting-payment-gateway']))
                    <li class="sidebar-item has-sub
                               {{ (Request::segment(1) == 'setting_banner' ||
                                   Request::segment(1) == 'setting_shipping_fee' ||
                                   Request::segment(1) == 'setting_cod_address' ||
                                   Request::segment(1) == 'setting_pick_up_address' ||
                                   Request::segment(1) == 'website_setting' ||
                                   Request::segment(1) == 'setting_home_page' ||
                                   Request::segment(1) == 'setting_website_messages' ||
                                   Request::segment(1) == 'setting_second_banner' ||
                                   Request::segment(1) == 'setting_home_video' ||
                                   Request::segment(1) == 'setting_home_overview' ||
                                   Request::segment(1) == 'setting_featured_product_title' ||
                                   Request::segment(1) == 'setting_website_countries' ||
                                   Request::segment(1) == 'setting_payment_gateway' ||
                                   Request::segment(1) == 'setting_header' ||
                                   Request::segment(1) == 'setting_einvoice') ? 'active' : '' }}">
                        <a href="#" class="sidebar-link">
                            <i class="bi bi-gear"></i>
                            <span> {{ isset($data['backendlang']['backendlang']['Settings_Manage']) ? $data['backendlang']['backendlang']['Settings_Manage'] : ''  }}</span>
                        </a>

                        <ul class="submenu">
                            {{-- home --}}
                            @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['setting-banner-list']))
                            <li class="submenu-item has-sub
                                    {{ (Request::segment(1) == 'setting_website_messages' ||
                                        Request::segment(1) == 'setting_banner' || 
                                        Request::segment(1) == 'setting_second_banner' ||
                                        Request::segment(1) == 'setting_home_page' || 
                                        Request::segment(1) == 'setting_home_video' ||
                                        Request::segment(1) == 'setting_home_overview'||
                                        Request::segment(1) == 'setting_header') ? 'active' : '' }}">
                                <a href="#" class="submenu-link">
                                    {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] : ''  }}
                                </a>

                                <ul class="submenu submenu-level-2">
                                    @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['setting-website-message-list']))
                                    <li class="submenu-item {{ (Request::segment(1) == 'setting_website_messages') ? 'active' : '' }}">
                                        <a href="{{ route('setting_website_messages') }}" class="submenu-link">
                                            {{ isset($data['backendlang']['backendlang']['Website_Messages']) ? $data['backendlang']['backendlang']['Website_Messages'] : ''  }}
                                        </a>
                                    </li>
                                    @endif

                                    @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['setting-banner-list']))
                                    <li class="submenu-item {{ (Request::segment(1) == 'setting_banner') ? 'active' : '' }}">
                                        <a href="{{ route('setting_banner') }}" class="submenu-link">
                                            {{ isset($data['backendlang']['backendlang']['First_Banner']) ? $data['backendlang']['backendlang']['First_Banner'] : ''  }}
                                        </a>
                                    </li>
                                    @endif

                                    @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['setting-second-banner']))
                                    <li class="submenu-item {{ (Request::segment(1) == 'setting_second_banner') ? 'active' : '' }}">
                                        <a href="{{ route('setting_second_banner') }}" class="submenu-link">
                                            {{ isset($data['backendlang']['backendlang']['Second_Banner']) ? $data['backendlang']['backendlang']['Second_Banner'] : ''  }}
                                        </a>
                                    </li>
                                    @endif

                                    @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['setting-home-page-list']))
                                    <li class="submenu-item {{ (Request::segment(1) == 'setting_home_page') ? 'active' : '' }}">
                                        <a href="{{ route('setting_home_page') }}" class="submenu-link">
                                            {{ isset($data['backendlang']['backendlang']['Two_Highlight']) ? $data['backendlang']['backendlang']['Two_Highlight'] : ''  }}
                                        </a>
                                    </li>
                                    @endif

                                    @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['setting-home-videos']))
                                    <li class="submenu-item {{ (Request::segment(1) == 'setting_home_video') ? 'active' : '' }}">
                                        <a href="{{ route('setting_home_video') }}" class="submenu-link">
                                            {{ isset($data['backendlang']['backendlang']['Video']) ? $data['backendlang']['backendlang']['Video'] : ''  }}
                                        </a>
                                    </li>
                                    @endif

                                    @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['setting-home-overview']))
                                    <li class="submenu-item {{ (Request::segment(1) == 'setting_home_overview') ? 'active' : '' }}">
                                        <a href="{{ route('setting_home_overview') }}" class="submenu-link">
                                            {{ isset($data['backendlang']['backendlang']['Overview']) ? $data['backendlang']['backendlang']['Overview'] : ''  }}
                                        </a>
                                    </li>
                                    @endif

                                    @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['setting-header-image']))
                                    <li class="submenu-item {{ (Request::segment(1) == 'setting_header') ? 'active' : '' }}">
                                        <a href="{{ route('setting_header') }}" class="submenu-link">
                                            {{ isset($data['backendlang']['backendlang']['Setting_Header']) ? $data['backendlang']['backendlang']['Setting_Header'] : ''  }}
                                        </a>
                                    </li>
                                    @endif

                                    @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['setting-featured-product-title']))
                                    <li class="submenu-item {{ (Request::segment(1) == 'setting_featured_product_title') ? 'active' : '' }}">
                                        <a href="{{ route('setting_featured_product_title') }}" class="submenu-link">
                                            {{ isset($data['backendlang']['backendlang']['Setting_Featured_Product_Title']) ? $data['backendlang']['backendlang']['Setting_Featured_Product_Title'] : ''  }}
                                        </a>
                                    </li>
                                    @endif

                                </ul>
                            </li>
                            @endif

                             {{-- shipping / pickup --}}
                            @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['setting-banner-list']))
                            <li class="submenu-item has-sub
                                    {{ (Request::segment(1) == 'setting_shipping_fee' ||
                                        Request::segment(1) == 'setting_cod_address' || 
                                        Request::segment(1) == 'setting_pick_up_address') ? 'active' : '' }}">
                                <a href="#" class="submenu-link">
                                    {{ isset($data['backendlang']['backendlang']['Shipping_Pickup']) ? $data['backendlang']['backendlang']['Shipping_Pickup'] : ''  }}
                                </a>

                                <ul class="submenu submenu-level-2">
                                    @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['setting-shipping-fee-list']))
                                    <li class="submenu-item {{ (Request::segment(1) == 'setting_shipping_fee') ? 'active' : '' }}">
                                        <a href="{{ route('setting_shipping_fee') }}" class="submenu-link">
                                            {{ isset($data['backendlang']['backendlang']['Shipping_Fee']) ? $data['backendlang']['backendlang']['Shipping_Fee'] : ''  }}
                                        </a>
                                    </li>
                                    @endif

                                    @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['pickup-list']))
                                    <li class="submenu-item {{ (Request::segment(1) == 'setting_cod_address') ? 'active' : '' }}">
                                        <a href="{{ route('setting_cod_address') }}" class="submenu-link">
                                            {{ isset($data['backendlang']['backendlang']['pickup_address']) ? $data['backendlang']['backendlang']['pickup_address'] : ''  }}
                                        </a>
                                    </li>
                                    @endif

                                    @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['easyparcel-pickup-list']))
                                    <li class="submenu-item {{ (Request::segment(1) == 'setting_pick_up_address') ? 'active' : '' }}">
                                        <a href="{{ route('setting_pick_up_address') }}" class="submenu-link">
                                            {{ isset($data['backendlang']['backendlang']['pickup_address_(Easyparcel)']) ? $data['backendlang']['backendlang']['pickup_address_(Easyparcel)'] : ''  }}
                                        </a>
                                    </li>
                                    @endif
                                </ul>
                            </li>
                            @endif

                             {{-- website --}}
                            @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['setting-banner-list']))
                            <li class="submenu-item has-sub
                                    {{ (Request::segment(1) == 'setting_website_countries' ||
                                        Request::segment(1) == 'setting_payment_gateway') ? 'active' : '' }}">
                                <a href="#" class="submenu-link">
                                    {{ isset($data['backendlang']['backendlang']['Website']) ? $data['backendlang']['backendlang']['Website'] : ''  }}
                                </a>

                                <ul class="submenu submenu-level-2">
                                    @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['setting-website-countries']))
                                    <li class="submenu-item {{ (Request::segment(1) == 'setting_website_countries') ? 'active' : '' }}">
                                        <a href="{{ route('setting_website_countries') }}" class="submenu-link">
                                            {{ isset($data['backendlang']['backendlang']['Country_Setting']) ? $data['backendlang']['backendlang']['Country_Setting'] : ''  }}
                                        </a>
                                    </li>
                                    @endif

                                    @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['setting-payment-gateway']))
                                    <li class="submenu-item {{ (Request::segment(1) == 'setting_payment_gateway') ? 'active' : '' }}">
                                        <a href="{{ route('setting_payment_gateway') }}" class="submenu-link">
                                            {{ isset($data['backendlang']['backendlang']['Payment_Gateway_Setting']) ? $data['backendlang']['backendlang']['Payment_Gateway_Setting'] : ''  }}
                                        </a>
                                    </li>
                                    @endif
                                </ul>
                            </li>
                            @endif

                            {{-- finance --}}
                            @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['setting-banner-list']))
                            <li class="submenu-item has-sub
                                    {{ (Request::segment(1) == 'setting_einvoice' ||
                                        Request::segment(1) == 'setting_auto_withdrawal') ? 'active' : '' }}">
                                <a href="#" class="submenu-link">
                                    {{ isset($data['backendlang']['backendlang']['Finance']) ? $data['backendlang']['backendlang']['Finance'] : ''  }}
                                </a>

                                <ul class="submenu submenu-level-2">
                                    @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['setting-einvoice']))
                                    <li class="submenu-item {{ (Request::segment(1) == 'setting_einvoice') ? 'active' : '' }}">
                                        <a href="{{ route('setting_einvoice') }}" class="submenu-link">
                                            {{ isset($data['backendlang']['backendlang']['e-Invoice_Setting']) ? $data['backendlang']['backendlang']['e-Invoice_Setting'] : ''  }}
                                        </a>
                                    </li>
                                    @endif

                                    @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['setting-auto-withdrawal']))
                                    <li class="submenu-item {{ (Request::segment(1) == 'setting_auto_withdrawal') ? 'active' : '' }}">
                                        <a href="{{ route('setting_auto_withdrawal') }}" class="submenu-link">
                                            {{ isset($data['backendlang']['backendlang']['Setting_Auto_Withdrawal']) ? $data['backendlang']['backendlang']['Setting_Auto_Withdrawal'] : ''  }}
                                        </a>
                                    </li> 
                                    @endif
                                </ul>
                            </li>
                            @endif

                            {{-- theme --}}
                            @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['setting-banner-list']))
                            <li class="submenu-item has-sub
                                    {{ (Request::segment(1) == 'setting_colour') ? 'active' : '' }}">
                                <a href="#" class="submenu-link">
                                    {{ isset($data['backendlang']['backendlang']['Theme']) ? $data['backendlang']['backendlang']['Theme'] : ''  }}
                                </a>

                                <ul class="submenu submenu-level-2">
                                    @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['setting-colour']))
                                    <li class="submenu-item {{ (Request::segment(1) == 'setting_colour') ? 'active' : '' }}">
                                        <a href="{{ route('setting_colour') }}" class="submenu-link">
                                            {{ isset($data['backendlang']['backendlang']['Setting_Website_Theme_Colour']) ? $data['backendlang']['backendlang']['Setting_Website_Theme_Colour'] : ''  }}
                                        </a>
                                    </li>
                                    @endif
                                </ul>
                            </li>
                            @endif
                                
                            @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['setting-banner']) || 
                                !empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['setting-shipping-fee-list']) || 
                                !empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['easyparcel-pickup-list']) || 
                                !empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['setting-home-page-list']) || 
                                !empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['website-setting-list']))
                            <li class="submenu-item {{ (Request::segment(1) == 'website_setting') ? 'active' : '' }}">
                                <a href="{{ route('website_setting') }}" class="submenu-link">
                                    {{ isset($data['backendlang']['backendlang']['Flow_Setting']) ? $data['backendlang']['backendlang']['Flow_Setting'] : ''  }}
                                </a>
                            </li>
                            @endif

                        </ul>
                    </li>
                @endif

                @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['setting-quiz']) || 
                    !empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['quiz-result']))
                  	<li class="sidebar-item has-sub 
                               {{ (Request::segment(1) == 'quizs' ||
                                   Request::segment(1) == 'quiz_records_index' ||
                                   Request::segment(1) == 'quiz_records_view') ? 'active' : '' }}">
                    	<a href="#" class="sidebar-link">
                      		<i class="bi bi-clipboard-check"></i>

                      		<span>{{ isset($data['backendlang']['backendlang']['Quiz_Manage']) ? $data['backendlang']['backendlang']['Quiz_Manage'] : ''  }}</span>
                    	</a>

                    	<ul class="submenu">
                            @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['setting-quiz']))
                            <li class="submenu-item {{ (Request::segment(1) == 'quizs') ? 'active' : '' }}">
                                <a href="{{ route('quiz.quizs.index') }}" class="submenu-link">
                                    {{ isset($data['backendlang']['backendlang']['Add_Quiz']) ? $data['backendlang']['backendlang']['Add_Quiz'] : ''  }}
                                </a>
                            </li>
                            @endif

                            @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['quiz-result']))
                            <li class="submenu-item {{ (Request::segment(1) == 'quiz_records_index' ||
                                                        Request::segment(1) == 'quiz_records_view') ? 'active' : '' }}">
                                <a href="{{ route('quiz_records_index') }}" class="submenu-link">
                                    {{ isset($data['backendlang']['backendlang']['View_Records']) ? $data['backendlang']['backendlang']['View_Records'] : ''  }}
                                </a>
                            </li>
                            @endif
                		</ul>
                  	</li>
                @endif

                @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['setting-blog']))
                <li class="sidebar-item {{ (Request::segment(1) == 'blogs') ? 'active' : '' }}">
                    <a href="{{ route('blog.blogs.index') }}" class="sidebar-link">
                        <i class="bi bi-newspaper"></i>
                        <span>{{ isset($data['backendlang']['backendlang']['Blogs']) ? $data['backendlang']['backendlang']['Blogs'] : ''  }}</span>
                    </a>
                </li>
                @endif

                @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['setting-faqs']))
                <li class="sidebar-item {{ (Request::segment(1) == 'setting_all_faqs') ? 'active' : '' }}">
                    <a href="{{ route('setting_all_faq.setting_all_faqs.index') }}" class="sidebar-link">
                        <i class="bi bi-question-circle"></i>
                        <span>{{ isset($data['backendlang']['backendlang']['FAQs']) ? $data['backendlang']['backendlang']['FAQs'] : ''  }}</span>
                    </a>
                </li>
                @endif

                <!-- <li class="sidebar-item {{ (Request::segment(1) == 'user_permissions') ? 'active' : '' }}">
                    <a href="{{ route('user_permission.user_permissions.index') }}" class="sidebar-link">
                        <i class="bi bi-file-lock"></i>
                        <span>User Permission</span>
                    </a>
                </li> -->

                <li class="sidebar-item">
                    <a  href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="sidebar-link nav-link">
                        <i class="bi bi-arrow-bar-right"></i>
                        <span>{{ isset($data['backendlang']['backendlang']['Logout']) ? $data['backendlang']['backendlang']['Logout'] : ''  }}</span>
                    </a>
                    <form id="logout-form" action="{{ route('admin_logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>
@endif