@extends('layouts.admin_app')
<style type="text/css">
	.vertical-tree{
	}
	.vertical-tree ul{
	    padding-left: 30px;
	}
	.vertical-tree li {
	    margin: 0px 0;
	    list-style-type: none;
	    position: relative;
	    padding: 20px 5px 0px 5px;
	}
	.vertical-tree li::before{
	    content: '';
	    position: absolute; 
	    top: 0;
	    width: 1px; 
	    height: 100%;
	    right: auto; 
	    left: -20px;
	    border-left: 2px solid #ccc;
	    bottom: 50px;
	}
	.vertical-tree li::after{
	    content: '';
	    position: absolute; 
	    top: 34px; 
	    width: 25px; 
	    height: 20px;
	    right: auto; 
	    left: -20px;
	    border-top: 2px solid #ccc;
	}
	.vertical-tree li a{
	    display: inline-block;
	    padding: 8px 30px;
	    text-decoration: none;
	    background-color: #e1eafc;
	    color: #5a8dee;
	    border: 1px solid #e1eafc;
	    font-size: 13px;
	    border-radius: 4px;
	}
	.vertical-tree li .parent{
	    background-color: #ebebeb;
	    border: 1px solid #ebebeb;
	    cursor: auto;
	}
	.vertical-tree li .parent:hover{
	    background-color: #ebebeb;
	    border-color: #ebebeb;
	    color: #5a8dee;
	}
	.vertical-tree > ul > li::before, 
	.vertical-tree > ul > li::after{
	    border: 0;
	}
	.vertical-tree li:last-child::before{ 
	        height: 34px;
	}
	.vertical-tree li a:hover, 
	.vertical-tree li a:hover+ul li a {
	    background-color: #5a8dee;
	    color: #fff;
	    border: 1px solid #5a8dee;
	}
	.vertical-tree li a:hover+ul li::after, 
	.vertical-tree li a:hover+ul li::before, 
	.vertical-tree li a:hover+ul::before, 
	.vertical-tree li a:hover+ul ul::before{
	    border-color:  #fbba00;
	}

	.vertical-tree li a.active {
		background-color: #5a8dee;
	    color: #fff;
	    border: 1px solid #5a8dee;
	}
</style>

@section('content')
<div class="row">
	@foreach($selects as $select)
			<div class="col-6 form-group">
				<div class="container-box">
					<h3>
						{{ $select->group_name }}
					</h3>
					<hr>
					<div class="vertical-tree">
					    <ul>
					        <li>
					            <a href="javascript:void(0);" class="permission-control {{ isset($get_permission[$select->id]['dashboard']) && !empty($get_permission[$select->id]['dashboard']) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="dashboard">{{ isset($data['backendlang']['backendlang']['dashboard']) ? $data['backendlang']['backendlang']['dashboard'] :'' }}</a>
					        </li>
					        <li>
					            <a href="javascript:void(0);" class="permission-control {{ isset($get_permission[$select->id]['company-profile']) && !empty($get_permission[$select->id]['company-profile']) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="company-profile"> {{ isset($data['backendlang']['backendlang']['Company_Profile']) ? $data['backendlang']['backendlang']['Company_Profile'] :'' }}</a>
					        </li>
					        <li>
					            <a href="javascript:void(0);" class="permission-control {{ isset($get_permission[$select->id]['cashier-screen']) && !empty($get_permission[$select->id]['cashier-screen']) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="cashier-screen">{{ isset($data['backendlang']['backendlang']['Cashier']) ? $data['backendlang']['backendlang']['Cashier'] :'' }}</a>
					        </li>
					        <li>
					        	<a href="javascript:void(0);" class="permission-control parent" data-id="{{ $select->id }}" data-page="">{{ isset($data['backendlang']['backendlang']['userManage']) ? $data['backendlang']['backendlang']['userManage'] :'' }}</a>
					        	<ul>
					        		<li>
					        			<a href="javascript:void(0);" class="permission-control parent">
					        				{{ isset($data['backendlang']['backendlang']['Agents']) ? $data['backendlang']['backendlang']['Agents'] :'' }}
					        			</a>
					        			<ul>
							        		<li>
					        					<a href="javascript:void(0);" class="permission-control parent">
							        				{{ isset($data['backendlang']['backendlang']['Agent_List']) ? $data['backendlang']['backendlang']['Agent_List'] :'' }}
							        			</a>
							        			<ul>
									        		<li>
									        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['agent-insert'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="agent-insert">
									        				{{ isset($data['backendlang']['backendlang']['Insert']) ? $data['backendlang']['backendlang']['Insert'] :'' }}
									        			</a>
									        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['agent-list'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="agent-list">
									        				{{ isset($data['backendlang']['backendlang']['View']) ? $data['backendlang']['backendlang']['View'] :'' }}
									        			</a>
									        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['agent-edit'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="agent-edit">
									        				{{ isset($data['backendlang']['backendlang']['Edit']) ? $data['backendlang']['backendlang']['Edit'] :'' }}
									        			</a>
									        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['agent-delete'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="agent-delete">
									        				{{ isset($data['backendlang']['backendlang']['Delete']) ? $data['backendlang']['backendlang']['Delete'] :'' }}
									        			</a>
									        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['agent-affiliate'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="agent-affiliate">
									        				{{ isset($data['backendlang']['backendlang']['Affiliate']) ? $data['backendlang']['backendlang']['Affiliate'] :'' }}
									        			</a>
									        		</li>
									        	</ul>
							        		</li>
							        		<li>
					        					<a href="javascript:void(0);" class="permission-control parent">
							        				{{ isset($data['backendlang']['backendlang']['agentWallet']) ? $data['backendlang']['backendlang']['agentWallet'] :'' }}
							        			</a>
							        			<ul>
									        		<li>
									        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['agent-wallet-list'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="agent-wallet-list">
									        				{{ isset($data['backendlang']['backendlang']['View']) ? $data['backendlang']['backendlang']['View'] :'' }}
									        			</a>
									        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['agent-wallet-history'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="agent-wallet-history">
									        				{{ isset($data['backendlang']['backendlang']['History']) ? $data['backendlang']['backendlang']['History'] :'' }}
									        			</a>
									        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['agent-wallet-adjust'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="agent-wallet-adjust">
									        				{{ isset($data['backendlang']['backendlang']['Adjust_Coin']) ? $data['backendlang']['backendlang']['Adjust_Coin'] :'' }}
									        			</a>
									        		</li>
									        	</ul>
							        		</li>
							        		<li>
					        					<a href="javascript:void(0);" class="permission-control parent">
							        				{{ isset($data['backendlang']['backendlang']['Pending_Agent']) ? $data['backendlang']['backendlang']['Pending_Agent'] :'' }}
							        			</a>
							        			<ul>
									        		<li>
									        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['agent-pending-list'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="agent-pending-list">
									        				{{ isset($data['backendlang']['backendlang']['View']) ? $data['backendlang']['backendlang']['View'] :'' }}
									        			</a>
									        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['agent-approve-reject'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="agent-approve-reject">
									        				{{ isset($data['backendlang']['backendlang']['Approve_Reject']) ? $data['backendlang']['backendlang']['Approve_Reject'] :'' }}
									        			</a>
									        		</li>
									        	</ul>
							        		</li>
							        	</ul>
					        		</li>
					        		<li>
					        			<a href="javascript:void(0);" class="permission-control parent">
					        				{{ isset($data['backendlang']['backendlang']['members']) ? $data['backendlang']['backendlang']['members'] :'' }}
					        			</a>
					        			<ul>
							        		<li>
					        					<a href="javascript:void(0);" class="permission-control parent">
							        				{{ isset($data['backendlang']['backendlang']['Member_List']) ? $data['backendlang']['backendlang']['Member_List'] :'' }}
							        			</a>
							        			<ul>
									        		<li>
									        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['member-insert'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="member-insert">
									        				{{ isset($data['backendlang']['backendlang']['Insert']) ? $data['backendlang']['backendlang']['Insert'] :'' }}
									        			</a>
									        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['member-list'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="member-list">
									        				{{ isset($data['backendlang']['backendlang']['View']) ? $data['backendlang']['backendlang']['View'] :'' }}
									        			</a>
									        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['member-edit'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="member-edit">
									        				{{ isset($data['backendlang']['backendlang']['Edit']) ? $data['backendlang']['backendlang']['Edit'] :'' }}
									        			</a>
									        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['member-delete'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="member-delete">
									        				{{ isset($data['backendlang']['backendlang']['Delete']) ? $data['backendlang']['backendlang']['Delete'] :'' }}
									        			</a>
									        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['member-affiliate'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="member-affiliate">
									        				{{ isset($data['backendlang']['backendlang']['Affiliate']) ? $data['backendlang']['backendlang']['Affiliate'] :'' }}
									        			</a>
									        		</li>
									        	</ul>
							        		</li>
							        		<li>
					        					<a href="javascript:void(0);" class="permission-control parent">
							        				{{ isset($data['backendlang']['backendlang']['memberWallet']) ? $data['backendlang']['backendlang']['memberWallet'] :'' }}
							        			</a>
							        			<ul>
									        		<li>
									        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['member-wallet-list'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="member-wallet-list">
									        				{{ isset($data['backendlang']['backendlang']['View']) ? $data['backendlang']['backendlang']['View'] :'' }}
									        			</a>
									        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['member-wallet-history'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="member-wallet-history">
									        				{{ isset($data['backendlang']['backendlang']['History']) ? $data['backendlang']['backendlang']['History'] :'' }}
									        			</a>
									        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['member-wallet-adjust'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="member-wallet-adjust">
									        				{{ isset($data['backendlang']['backendlang']['Adjust_Coin']) ? $data['backendlang']['backendlang']['Adjust_Coin'] :'' }}
									        			</a>
									        		</li>
									        	</ul>
							        		</li>
							        		<li>
					        					<a href="javascript:void(0);" class="permission-control parent">
							        				{{ isset($data['backendlang']['backendlang']['Pending_Member']) ? $data['backendlang']['backendlang']['Pending_Member'] :'' }}
							        			</a>
							        			<ul>
									        		<li>
									        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['member-pending-list'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="member-pending-list">
									        					{{ isset($data['backendlang']['backendlang']['View']) ? $data['backendlang']['backendlang']['View'] :'' }}
									        			</a>
									        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['member-approve-reject'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="member-approve-reject">
									        					{{ isset($data['backendlang']['backendlang']['Approve_Reject']) ? $data['backendlang']['backendlang']['Approve_Reject'] :'' }}
									        			</a>
									        		</li>
									        	</ul>
							        		</li>
							        	</ul>
					        		</li>
					        		<li>
					        			<a href="javascript:void(0);" class="permission-control parent">
					        				{{ isset($data['backendlang']['backendlang']['merchants']) ? $data['backendlang']['backendlang']['merchants'] :'' }}
					        			</a>
					        			<ul>
							        		<li>
					        					<a href="javascript:void(0);" class="permission-control parent">
							        				{{ isset($data['backendlang']['backendlang']['merchantList']) ? $data['backendlang']['backendlang']['merchantList'] :'' }}
							        			</a>
							        			<ul>
									        		<li>
									        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['merchant-insert'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="merchant-insert">
									        				{{ isset($data['backendlang']['backendlang']['Insert']) ? $data['backendlang']['backendlang']['Insert'] :'' }}
									        			</a>
									        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['merchant-list'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="merchant-list">
									        				{{ isset($data['backendlang']['backendlang']['View']) ? $data['backendlang']['backendlang']['View'] :'' }}
									        			</a>
									        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['merchant-edit'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="merchant-edit">
									        				{{ isset($data['backendlang']['backendlang']['Edit']) ? $data['backendlang']['backendlang']['Edit'] :'' }}
									        			</a>
									        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['merchant-delete'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="merchant-delete">
									        				{{ isset($data['backendlang']['backendlang']['Delete']) ? $data['backendlang']['backendlang']['Delete'] :'' }}
									        			</a>
									        			<!-- <a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['merchant-affiliate'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="merchant-affiliate">
									        				Affiliate
									        			</a> -->
									        		</li>
									        	</ul>
							        		</li>
							        		<li>
					        					<a href="javascript:void(0);" class="permission-control parent">
							        				{{ isset($data['backendlang']['backendlang']['Pending_Merchant']) ? $data['backendlang']['backendlang']['Pending_Merchant'] :'' }}
							        			</a>
							        			<ul>
									        		<li>
									        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['merchant-pending-list'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="merchant-pending-list">
									        				{{ isset($data['backendlang']['backendlang']['View']) ? $data['backendlang']['backendlang']['View'] :'' }}
									        			</a>
									        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['merchant-approve-reject'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="merchant-approve-reject">
									        				{{ isset($data['backendlang']['backendlang']['Approve_Reject']) ? $data['backendlang']['backendlang']['Approve_Reject'] :'' }}
									        			</a>
									        		</li>
									        	</ul>
							        		</li>
							        	</ul>
					        		</li>
					        	</ul>
					        </li>
					        <li>
					        	<a href="javascript:void(0);" class="permission-control parent" data-id="{{ $select->id }}" data-page="">{{ isset($data['backendlang']['backendlang']['productsManage']) ? $data['backendlang']['backendlang']['productsManage'] :'' }}</a>
					        	<ul>
					        		<li>
					        			<a href="javascript:void(0);" class="permission-control parent" data-id="{{ $select->id }}" data-page="">{{ isset($data['backendlang']['backendlang']['products']) ? $data['backendlang']['backendlang']['products'] :'' }}</a>
							        	<ul>
							        		<li>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['product-insert'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="product-insert">{{ isset($data['backendlang']['backendlang']['Insert']) ? $data['backendlang']['backendlang']['Insert'] :'' }}</a>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['product-list'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="product-list">{{ isset($data['backendlang']['backendlang']['View']) ? $data['backendlang']['backendlang']['View'] :'' }}</a>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['product-edit'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="product-edit">{{ isset($data['backendlang']['backendlang']['Edit']) ? $data['backendlang']['backendlang']['Edit'] :'' }}</a>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['product-delete'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="product-delete">{{ isset($data['backendlang']['backendlang']['Delete']) ? $data['backendlang']['backendlang']['Delete'] :'' }}</a>
							        		</li>
							        	</ul>
					        		</li>
					        		<li>
					        			<a href="javascript:void(0);" class="permission-control parent" data-id="{{ $select->id }}" data-page="">{{ isset($data['backendlang']['backendlang']['packages']) ? $data['backendlang']['backendlang']['packages'] :'' }}</a>
							        	<ul>
							        		<li>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['package-insert'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="package-insert">{{ isset($data['backendlang']['backendlang']['Insert']) ? $data['backendlang']['backendlang']['Insert'] :'' }}</a>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['package-list'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="package-list">{{ isset($data['backendlang']['backendlang']['View']) ? $data['backendlang']['backendlang']['View'] :'' }}</a>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['package-edit'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="package-edit">{{ isset($data['backendlang']['backendlang']['Edit']) ? $data['backendlang']['backendlang']['Edit'] :'' }}</a>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['package-delete'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="package-delete">{{ isset($data['backendlang']['backendlang']['Delete']) ? $data['backendlang']['backendlang']['Delete'] :'' }}</a>
							        		</li>
							        	</ul>
					        		</li>
					        	</ul>
					        </li>
					        <li>
					        	<a href="javascript:void(0);" class="permission-control parent" data-id="{{ $select->id }}" data-page="">{{ isset($data['backendlang']['backendlang']['unitControl']) ? $data['backendlang']['backendlang']['unitControl'] :'' }}</a>
					        	<ul>
					        		<li>
					        			<a href="javascript:void(0);" class="permission-control parent" data-id="{{ $select->id }}" data-page="">{{ isset($data['backendlang']['backendlang']['products']) ? $data['backendlang']['backendlang']['products'] :'' }}</a>
							        	<ul>
							        		<li>
							        			<a href="javascript:void(0);" class="permission-control parent">
							        				{{ isset($data['backendlang']['backendlang']['categories']) ? $data['backendlang']['backendlang']['categories'] :'' }}
							        			</a>
									        	<ul>
									        		<li>
									        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['category-insert'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="category-insert">{{ isset($data['backendlang']['backendlang']['Insert']) ? $data['backendlang']['backendlang']['Insert'] :'' }}</a>
									        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['category-list'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="category-list">{{ isset($data['backendlang']['backendlang']['View']) ? $data['backendlang']['backendlang']['View'] :'' }}</a>
									        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['category-edit'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="category-edit">{{ isset($data['backendlang']['backendlang']['Edit']) ? $data['backendlang']['backendlang']['Edit'] :'' }}</a>
									        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['category-delete'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="category-delete">{{ isset($data['backendlang']['backendlang']['Delete']) ? $data['backendlang']['backendlang']['Delete'] :'' }}</a>
									        		</li>
									        	</ul>
							        		</li>
							        	</ul>
							        		<li>
							        			<a href="javascript:void(0);" class="permission-control parent">
							        				{{ isset($data['backendlang']['backendlang']['subCategory']) ? $data['backendlang']['backendlang']['subCategory'] :'' }}
							        			</a>
									        	<ul>
									        		<li>
									        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['sub-category-insert'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="category-insert">{{ isset($data['backendlang']['backendlang']['Insert']) ? $data['backendlang']['backendlang']['Insert'] :'' }}</a>
									        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['sub-category-list'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="category-list">{{ isset($data['backendlang']['backendlang']['View']) ? $data['backendlang']['backendlang']['View'] :'' }}</a>
									        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['sub-category-edit'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="category-edit">{{ isset($data['backendlang']['backendlang']['Edit']) ? $data['backendlang']['backendlang']['Edit'] :'' }}</a>
									        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['sub-category-delete'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="category-delete">{{ isset($data['backendlang']['backendlang']['Delete']) ? $data['backendlang']['backendlang']['Delete'] :'' }}</a>
									        		</li>
									        	</ul>
							        		</li>
						        		<li>
						        			<a href="javascript:void(0);" class="permission-control parent" data-id="{{ $select->id }}" data-page="">{{ isset($data['backendlang']['backendlang']['Brands']) ? $data['backendlang']['backendlang']['Brands'] :'' }}</a>
								        	<ul>
								        		<li>
									        		<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['brand-insert'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="category-insert">{{ isset($data['backendlang']['backendlang']['Insert']) ? $data['backendlang']['backendlang']['Insert'] :'' }}</a>
									        		<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['brand-list'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="category-list">{{ isset($data['backendlang']['backendlang']['View']) ? $data['backendlang']['backendlang']['View'] :'' }}</a>
									        		<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['brand-edit'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="category-edit">{{ isset($data['backendlang']['backendlang']['Edit']) ? $data['backendlang']['backendlang']['Edit'] :'' }}</a>
									        		<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['brand-delete'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="category-delete">{{ isset($data['backendlang']['backendlang']['Delete']) ? $data['backendlang']['backendlang']['Delete'] :'' }}</a>
								        		</li>
								        	</ul>
						        		</li>
						        		<li>
						        			<a href="javascript:void(0);" class="permission-control parent" data-id="{{ $select->id }}" data-page=""> {{ isset($data['backendlang']['backendlang']['Setting_UOM']) ? $data['backendlang']['backendlang']['Setting_UOM'] :'' }}</a>
								        	<ul>
								        		<li>
								        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['setting-uom-insert'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="setting-uom-insert">{{ isset($data['backendlang']['backendlang']['Insert']) ? $data['backendlang']['backendlang']['Insert'] :'' }}</a>
								        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['setting-uom-list'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="setting-uom-list">{{ isset($data['backendlang']['backendlang']['View']) ? $data['backendlang']['backendlang']['View'] :'' }}</a>
								        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['setting-uom-edit'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="setting-uom-edit">{{ isset($data['backendlang']['backendlang']['Edit']) ? $data['backendlang']['backendlang']['Edit'] :'' }}</a>
								        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['setting-uom-delete'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="setting-uom-delete">{{ isset($data['backendlang']['backendlang']['Delete']) ? $data['backendlang']['backendlang']['Delete'] :'' }}</a>
								        		</li>
								        	</ul>
						        		</li>
					        		</li>
							    </ul>
					        </li>
					        <li>
					        	<a href="javascript:void(0);" class="permission-control parent" data-id="{{ $select->id }}" data-page="">{{ isset($data['backendlang']['backendlang']['marketingCentre']) ? $data['backendlang']['backendlang']['marketingCentre'] :'' }}</a>
					        	<ul>
					        		<li>
					        			<a href="javascript:void(0);" class="permission-control parent" data-id="{{ $select->id }}" data-page="">{{ isset($data['backendlang']['backendlang']['voucher_list']) ? $data['backendlang']['backendlang']['voucher_list'] :'' }}</a>
							        	<ul>
							        		<li>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['voucher-insert'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="voucher-insert">{{ isset($data['backendlang']['backendlang']['Insert']) ? $data['backendlang']['backendlang']['Insert'] :'' }}</a>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['voucher-list'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="voucher-list">{{ isset($data['backendlang']['backendlang']['View']) ? $data['backendlang']['backendlang']['View'] :'' }}</a>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['voucher-edit'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="voucher-edit">{{ isset($data['backendlang']['backendlang']['Edit']) ? $data['backendlang']['backendlang']['Edit'] :'' }}</a>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['voucher-delete'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="voucher-delete">{{ isset($data['backendlang']['backendlang']['Delete']) ? $data['backendlang']['backendlang']['Delete'] :'' }}</a>
							        		</li>
							        	</ul>
					        		</li>
					        		<li>
					        			<a href="javascript:void(0);" class="permission-control parent" data-id="{{ $select->id }}" data-page="">{{ isset($data['backendlang']['backendlang']['flash_sale']) ? $data['backendlang']['backendlang']['flash_sale'] :'' }}</a>
							        	<ul>
							        		<li>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['flash-sale-insert'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="flash-sale-insert">{{ isset($data['backendlang']['backendlang']['Insert']) ? $data['backendlang']['backendlang']['Insert'] :'' }}</a>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['flash-sale-list'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="flash-sale-list">{{ isset($data['backendlang']['backendlang']['View']) ? $data['backendlang']['backendlang']['View'] :'' }}</a>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['flash-sale-edit'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="flash-sale-edit">{{ isset($data['backendlang']['backendlang']['Edit']) ? $data['backendlang']['backendlang']['Edit'] :'' }}</a>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['flash-sale-delete'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="flash-sale-delete">{{ isset($data['backendlang']['backendlang']['Delete']) ? $data['backendlang']['backendlang']['Delete'] :'' }}</a>
							        		</li>
							        	</ul>
					        		</li>
					        		<li>
					        			<a href="javascript:void(0);" class="permission-control parent" data-id="{{ $select->id }}" data-page="">{{ isset($data['backendlang']['backendlang']['add_on_deal']) ? $data['backendlang']['backendlang']['add_on_deal'] :'' }}</a>
							        	<ul>
							        		<li>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['add-on-deal-insert'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="add-on-deal-insert">{{ isset($data['backendlang']['backendlang']['Insert']) ? $data['backendlang']['backendlang']['Insert'] :'' }}</a>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['add-on-deal-list'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="add-on-deal-list">{{ isset($data['backendlang']['backendlang']['View']) ? $data['backendlang']['backendlang']['View'] :'' }}</a>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['add-on-deal-edit'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="add-on-deal-edit">{{ isset($data['backendlang']['backendlang']['Edit']) ? $data['backendlang']['backendlang']['Edit'] :'' }}</a>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['add-on-deal-delete'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="add-on-deal-delete">{{ isset($data['backendlang']['backendlang']['Delete']) ? $data['backendlang']['backendlang']['Delete'] :'' }}</a>
							        		</li>
							        	</ul>
					        		</li>
					        		<li>
					        			<a href="javascript:void(0);" class="permission-control parent" data-id="{{ $select->id }}" data-page="">{{ isset($data['backendlang']['backendlang']['cart_link']) ? $data['backendlang']['backendlang']['cart_link'] :'' }}</a>
							        	<ul>
							        		<li>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['cart-links-insert'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="cart-links-insert">{{ isset($data['backendlang']['backendlang']['Insert']) ? $data['backendlang']['backendlang']['Insert'] :'' }}</a>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['cart-links-list'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="cart-links-list">{{ isset($data['backendlang']['backendlang']['View']) ? $data['backendlang']['backendlang']['View'] :'' }}</a>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['cart-links-edit'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="cart-links-edit">{{ isset($data['backendlang']['backendlang']['Edit']) ? $data['backendlang']['backendlang']['Edit'] :'' }}</a>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['cart-links-delete'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="cart-links-delete">{{ isset($data['backendlang']['backendlang']['Delete']) ? $data['backendlang']['backendlang']['Delete'] :'' }}</a>
							        		</li>
							        	</ul>
					        		</li>
					        	</ul>
					        </li>
					        <li>
					        	<a href="javascript:void(0);" class="permission-control parent" data-id="{{ $select->id }}" data-page="">{{ isset($data['backendlang']['backendlang']['Order_Manage']) ? $data['backendlang']['backendlang']['Order_Manage'] :'' }}</a>
					        	<ul>
					        		<li>
					        			<a href="javascript:void(0);" class="permission-control parent" data-id="{{ $select->id }}" data-page="">{{ isset($data['backendlang']['backendlang']['Transaction']) ? $data['backendlang']['backendlang']['Transaction'] :'' }}</a>
							        	<ul>
							        		<li>
					        					<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['transaction-insert'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="transaction-insert">{{ isset($data['backendlang']['backendlang']['Insert']) ? $data['backendlang']['backendlang']['Insert'] :'' }}</a>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['transaction-list'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="transaction-list">{{ isset($data['backendlang']['backendlang']['View']) ? $data['backendlang']['backendlang']['View'] :'' }}</a>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['transaction-edit'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="transaction-edit">{{ isset($data['backendlang']['backendlang']['Edit']) ? $data['backendlang']['backendlang']['Edit'] :'' }}</a>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['transaction-delete'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="transaction-delete">{{ isset($data['backendlang']['backendlang']['Delete']) ? $data['backendlang']['backendlang']['Delete'] :'' }}</a>
							        		</li>
							        	</ul>
					        		</li>
					        		<li>
					        			<a href="javascript:void(0);" class="permission-control parent" data-id="{{ $select->id }}" data-page="">{{ isset($data['backendlang']['backendlang']['Point_Transaction']) ? $data['backendlang']['backendlang']['Point_Transaction'] :'' }}</a>
							        	<ul>
							        		<li>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['point-transaction-list'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="point-transaction-list">{{ isset($data['backendlang']['backendlang']['View']) ? $data['backendlang']['backendlang']['View'] :'' }}</a>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['point-transaction-edit'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="point-transaction-edit">{{ isset($data['backendlang']['backendlang']['Edit']) ? $data['backendlang']['backendlang']['Edit'] :'' }}</a>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['point-transaction-delete'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="point-transaction-delete">{{ isset($data['backendlang']['backendlang']['Delete']) ? $data['backendlang']['backendlang']['Delete'] :'' }}</a>
							        		</li>
							        	</ul>
					        		</li>
					        		<li>
					        			<a href="javascript:void(0);" class="permission-control parent" data-id="{{ $select->id }}" data-page="">{{ isset($data['backendlang']['backendlang']['Withdrawal_List']) ? $data['backendlang']['backendlang']['Withdrawal_List'] :'' }}</a>
							        	<ul>
							        		<li>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['withdrawal-list'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="withdrawal-list">{{ isset($data['backendlang']['backendlang']['View']) ? $data['backendlang']['backendlang']['View'] :'' }}</a>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['withdrawal-edit'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="withdrawal-edit">{{ isset($data['backendlang']['backendlang']['Edit']) ? $data['backendlang']['backendlang']['Edit'] :'' }}</a>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['withdrawal-delete'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="withdrawal-delete">{{ isset($data['backendlang']['backendlang']['Delete']) ? $data['backendlang']['backendlang']['Delete'] :'' }}</a>
							        		</li>
							        	</ul>
					        		</li>
					        		<li>
					        			<a href="javascript:void(0);" class="permission-control parent" data-id="{{ $select->id }}" data-page="">{{ isset($data['backendlang']['backendlang']['topup_list']) ? $data['backendlang']['backendlang']['topup_list'] :'' }}</a>
							        	<ul>
							        		<li>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['topup-list'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="topup-list">{{ isset($data['backendlang']['backendlang']['View']) ? $data['backendlang']['backendlang']['View'] :'' }}</a>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['topup-edit'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="topup-edit">{{ isset($data['backendlang']['backendlang']['Edit']) ? $data['backendlang']['backendlang']['Edit'] :'' }}</a>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['topup-delete'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="topup-delete">{{ isset($data['backendlang']['backendlang']['Delete']) ? $data['backendlang']['backendlang']['Delete'] :'' }}</a>
							        		</li>
							        	</ul>
					        		</li>
					        	</ul>
					        </li>
					        <li>
					        	<a href="javascript:void(0);" class="permission-control parent" data-id="{{ $select->id }}" data-page="">{{ isset($data['backendlang']['backendlang']['report_manage']) ? $data['backendlang']['backendlang']['report_manage'] :'' }}</a>
					        	<ul>
					        		<li>
					        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['sales-report'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="sales-report">{{ isset($data['backendlang']['backendlang']['Sales_Report']) ? $data['backendlang']['backendlang']['Sales_Report'] :'' }}</a>
					        		</li>
					        		<li>
					        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['order-report'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="order-report">{{ isset($data['backendlang']['backendlang']['order_report']) ? $data['backendlang']['backendlang']['order_report'] :'' }}</a>
					        		</li>
					        		<li>
					        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['commission-report'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="commission-report">{{ isset($data['backendlang']['backendlang']['Commission_Report']) ? $data['backendlang']['backendlang']['Commission_Report'] :'' }}</a>
					        		</li>
					        		<li>
					        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['stock-report'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="stock-report">{{ isset($data['backendlang']['backendlang']['Stock_Report_List']) ? $data['backendlang']['backendlang']['Stock_Report_List'] :'' }}</a>
					        		</li>
					        		<li>
					        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['agent-report'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="agent-report">{{ isset($data['backendlang']['backendlang']['Agent_Report']) ? $data['backendlang']['backendlang']['Agent_Report'] :'' }}</a>
					        		</li>
					        	</ul>
					        </li>
					        <li>
					        	<a href="javascript:void(0);" class="permission-control parent" data-id="{{ $select->id }}" data-page="">{{ isset($data['backendlang']['backendlang']['Bonus_Manage']) ? $data['backendlang']['backendlang']['Bonus_Manage'] :'' }}</a>
					        	<ul>
					        		<li>
					        			<a href="javascript:void(0);" class="permission-control parent" data-id="{{ $select->id }}" data-page="">{{ isset($data['backendlang']['backendlang']['Agent_Level']) ? $data['backendlang']['backendlang']['Agent_Level'] :'' }}</a>
							        	<ul>
							        		<li>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['agent-lvl-insert'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="agent-lvl-insert">{{ isset($data['backendlang']['backendlang']['Insert']) ? $data['backendlang']['backendlang']['Insert'] :'' }}</a>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['agent-lvl-list'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="agent-lvl-list">{{ isset($data['backendlang']['backendlang']['View']) ? $data['backendlang']['backendlang']['View'] :'' }}</a>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['agent-lvl-edit'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="agent-lvl-edit">{{ isset($data['backendlang']['backendlang']['Edit']) ? $data['backendlang']['backendlang']['Edit'] :'' }}</a>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['agent-lvl-delete'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="agent-lvl-delete">{{ isset($data['backendlang']['backendlang']['Delete']) ? $data['backendlang']['backendlang']['Delete'] :'' }}</a>
							        		</li>
							        	</ul>
					        		</li>
					        		<li>
					        			<a href="javascript:void(0);" class="permission-control parent" data-id="{{ $select->id }}" data-page="">{{ isset($data['backendlang']['backendlang']['Agent_Order_Rebate']) ? $data['backendlang']['backendlang']['Agent_Order_Rebate'] :'' }}</a>
							        	<ul>
							        		<li>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['agent-order-rebate-insert'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="agent-order-rebate-insert">{{ isset($data['backendlang']['backendlang']['Insert']) ? $data['backendlang']['backendlang']['Insert'] :'' }}</a>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['agent-order-rebate-list'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="agent-order-rebate-list">{{ isset($data['backendlang']['backendlang']['View']) ? $data['backendlang']['backendlang']['View'] :'' }}</a>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['agent-order-rebate-edit'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="agent-order-rebate-edit">{{ isset($data['backendlang']['backendlang']['Edit']) ? $data['backendlang']['backendlang']['Edit'] :'' }}</a>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['agent-order-rebate-delete'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="agent-order-rebate-delete">{{ isset($data['backendlang']['backendlang']['Delete']) ? $data['backendlang']['backendlang']['Delete'] :'' }}</a>
							        		</li>
							        	</ul>
					        		</li>
					        		<li>
					        			<a href="javascript:void(0);" class="permission-control parent" data-id="{{ $select->id }}" data-page="">{{ isset($data['backendlang']['backendlang']['Hierarchy_Bonus']) ? $data['backendlang']['backendlang']['Hierarchy_Bonus'] :'' }}</a>
							        	<ul>
							        		<li>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['hierarchy-bonus-insert'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="hierarchy-bonus-insert">{{ isset($data['backendlang']['backendlang']['Insert']) ? $data['backendlang']['backendlang']['Insert'] :'' }}</a>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['hierarchy-bonus-list'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="hierarchy-bonus-list">{{ isset($data['backendlang']['backendlang']['View']) ? $data['backendlang']['backendlang']['View'] :'' }}</a>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['hierarchy-bonus-edit'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="hierarchy-bonus-edit">{{ isset($data['backendlang']['backendlang']['Edit']) ? $data['backendlang']['backendlang']['Edit'] :'' }}</a>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['hierarchy-bonus-delete'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="hierarchy-bonus-delete">{{ isset($data['backendlang']['backendlang']['Delete']) ? $data['backendlang']['backendlang']['Delete'] :'' }}</a>
							        		</li>
							        	</ul>
					        		</li>
					        		<li>
					        			<a href="javascript:void(0);" class="permission-control parent" data-id="{{ $select->id }}" data-page="">{{ isset($data['backendlang']['backendlang']['Referral_Reward']) ? $data['backendlang']['backendlang']['Referral_Reward'] :'' }}</a>
							        	<ul>
							        		<li>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['referral-reward-insert'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="referral-reward-insert">{{ isset($data['backendlang']['backendlang']['Insert']) ? $data['backendlang']['backendlang']['Insert'] :'' }}</a>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['referral-reward-list'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="referral-reward-list">{{ isset($data['backendlang']['backendlang']['View']) ? $data['backendlang']['backendlang']['View'] :'' }}</a>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['referral-reward-edit'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="referral-reward-edit">{{ isset($data['backendlang']['backendlang']['Edit']) ? $data['backendlang']['backendlang']['Edit'] :'' }}</a>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['referral-reward-delete'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="referral-reward-delete">{{ isset($data['backendlang']['backendlang']['Delete']) ? $data['backendlang']['backendlang']['Delete'] :'' }}</a>
							        		</li>
							        	</ul>
					        		</li>
					        		<li>
					        			<a href="javascript:void(0);" class="permission-control parent" data-id="{{ $select->id }}" data-page="">{{ isset($data['backendlang']['backendlang']['Topup_Bonus']) ? $data['backendlang']['backendlang']['Topup_Bonus'] :'' }}</a>
							        	<ul>
							        		<li>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['topup-bonus-insert'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="topup-bonus-insert">{{ isset($data['backendlang']['backendlang']['Insert']) ? $data['backendlang']['backendlang']['Insert'] :'' }}</a>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['topup-bonus-list'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="topup-bonus-list">{{ isset($data['backendlang']['backendlang']['View']) ? $data['backendlang']['backendlang']['View'] :'' }}</a>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['topup-bonus-edit'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="topup-bonus-edit">{{ isset($data['backendlang']['backendlang']['Edit']) ? $data['backendlang']['backendlang']['Edit'] :'' }}</a>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['topup-bonus-delete'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="topup-bonus-delete">{{ isset($data['backendlang']['backendlang']['Delete']) ? $data['backendlang']['backendlang']['Delete'] :'' }}</a>
							        		</li>
							        	</ul>
					        		</li>
					        		<li>
					        			<a href="javascript:void(0);" class="permission-control parent" data-id="{{ $select->id }}" data-page="">{{ isset($data['backendlang']['backendlang']['Performance_Reward']) ? $data['backendlang']['backendlang']['Performance_Reward'] :'' }}</a>
							        	<ul>
							        		<li>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['performance-reward-insert'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="performance-reward-insert">{{ isset($data['backendlang']['backendlang']['Insert']) ? $data['backendlang']['backendlang']['Insert'] :'' }}</a>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['performance-reward-list'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="performance-reward-list">{{ isset($data['backendlang']['backendlang']['View']) ? $data['backendlang']['backendlang']['View'] :'' }}</a>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['performance-reward-edit'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="performance-reward-edit">{{ isset($data['backendlang']['backendlang']['Edit']) ? $data['backendlang']['backendlang']['Edit'] :'' }}</a>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['performance-reward-delete'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="performance-reward-delete">{{ isset($data['backendlang']['backendlang']['Delete']) ? $data['backendlang']['backendlang']['Delete'] :'' }}</a>
							        		</li>
							        	</ul>
					        		</li>
					        		<li>
					        			<a href="javascript:void(0);" class="permission-control parent" data-id="{{ $select->id }}" data-page="">{{ isset($data['backendlang']['backendlang']['Team_Reward']) ? $data['backendlang']['backendlang']['Team_Reward'] :'' }}</a>
							        	<ul>
							        		<li>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['team-reward-insert'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="team-reward-insert">{{ isset($data['backendlang']['backendlang']['Insert']) ? $data['backendlang']['backendlang']['Insert'] :'' }}</a>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['team-reward-list'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="team-reward-list">{{ isset($data['backendlang']['backendlang']['View']) ? $data['backendlang']['backendlang']['View'] :'' }}</a>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['team-reward-edit'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="team-reward-edit">{{ isset($data['backendlang']['backendlang']['Edit']) ? $data['backendlang']['backendlang']['Edit'] :'' }}</a>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['team-reward-delete'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="team-reward-delete">{{ isset($data['backendlang']['backendlang']['Delete']) ? $data['backendlang']['backendlang']['Delete'] :'' }}</a>
							        		</li>
							        	</ul>
					        		</li>
					        		<li>
					        			<a href="javascript:void(0);" class="permission-control parent" data-id="{{ $select->id }}" data-page="">{{ isset($data['backendlang']['backendlang']['Prize_Pool']) ? $data['backendlang']['backendlang']['Prize_Pool'] :'' }}</a>
							        	<ul>
							        		<li>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['prize-pool-insert'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="prize-pool-insert">{{ isset($data['backendlang']['backendlang']['Insert']) ? $data['backendlang']['backendlang']['Insert'] :'' }}</a>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['prize-pool-list'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="prize-pool-list">{{ isset($data['backendlang']['backendlang']['View']) ? $data['backendlang']['backendlang']['View'] :'' }}</a>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['prize-pool-edit'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="prize-pool-edit">{{ isset($data['backendlang']['backendlang']['Edit']) ? $data['backendlang']['backendlang']['Edit'] :'' }}</a>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['prize-pool-delete'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="prize-pool-delete">{{ isset($data['backendlang']['backendlang']['Delete']) ? $data['backendlang']['backendlang']['Delete'] :'' }}</a>
							        		</li>
							        	</ul>
					        		</li>
					        	</ul>
					        </li>
					        <li>
					        	<a href="javascript:void(0);" class="permission-control parent" data-id="{{ $select->id }}" data-page="">{{ isset($data['backendlang']['backendlang']['Settings_Manage']) ? $data['backendlang']['backendlang']['Settings_Manage'] :'' }}</a>
					        	<ul>
					        		<li>
					        			<a href="javascript:void(0);" class="permission-control parent" data-id="{{ $select->id }}" data-page="">{{ isset($data['backendlang']['backendlang']['Setting_Banner']) ? $data['backendlang']['backendlang']['Setting_Banner'] :'' }}</a>
							        	<ul>
							        		<li>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['setting-banner-insert'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="setting-banner-insert">{{ isset($data['backendlang']['backendlang']['Insert']) ? $data['backendlang']['backendlang']['Insert'] :'' }}</a>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['setting-banner-list'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="setting-banner-list">{{ isset($data['backendlang']['backendlang']['View']) ? $data['backendlang']['backendlang']['View'] :'' }}</a>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['setting-banner-edit'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="setting-banner-edit">{{ isset($data['backendlang']['backendlang']['Edit']) ? $data['backendlang']['backendlang']['Edit'] :'' }}</a>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['setting-banner-delete'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="setting-banner-delete">{{ isset($data['backendlang']['backendlang']['Delete']) ? $data['backendlang']['backendlang']['Delete'] :'' }}</a>
							        		</li>
							        	</ul>
					        		</li>
					        		<li>
					        			<a href="javascript:void(0);" class="permission-control parent" data-id="{{ $select->id }}" data-page="">{{ isset($data['backendlang']['backendlang']['Setting_Shipping_Fee']) ? $data['backendlang']['backendlang']['Setting_Shipping_Fee'] :'' }}</a>
							        	<ul>
							        		<li>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['setting-shipping-fee-insert'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="setting-shipping-fee-insert">{{ isset($data['backendlang']['backendlang']['Insert']) ? $data['backendlang']['backendlang']['Insert'] :'' }}</a>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['setting-shipping-fee-list'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="setting-shipping-fee-list">{{ isset($data['backendlang']['backendlang']['View']) ? $data['backendlang']['backendlang']['View'] :'' }}</a>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['setting-shipping-fee-edit'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="setting-shipping-fee-edit">{{ isset($data['backendlang']['backendlang']['Edit']) ? $data['backendlang']['backendlang']['Edit'] :'' }}</a>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['setting-shipping-fee-delete'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="setting-shipping-fee-delete">{{ isset($data['backendlang']['backendlang']['Delete']) ? $data['backendlang']['backendlang']['Delete'] :'' }}</a>
							        		</li>
							        	</ul>
					        		</li>
					        		<li>
					        			<a href="javascript:void(0);" class="permission-control parent" data-id="{{ $select->id }}" data-page="">{{ isset($data['backendlang']['backendlang']['Pickup_Address']) ? $data['backendlang']['backendlang']['Pickup_Address'] :'' }}</a>
							        	<ul>
							        		<li>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['pickup-list'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="pickup-list">{{ isset($data['backendlang']['backendlang']['View']) ? $data['backendlang']['backendlang']['View'] :'' }}</a>
							        		</li>
							        	</ul>
					        		</li>
					        		<li>
					        			<a href="javascript:void(0);" class="permission-control parent" data-id="{{ $select->id }}" data-page="">{{ isset($data['backendlang']['backendlang']['Pickup_Address']) ? $data['backendlang']['backendlang']['Pickup_Address'] :'' }} (EasyParcel)</a>
							        	<ul>
							        		<li>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['easyparcel-pickup-list'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="easyparcel-pickup-list">{{ isset($data['backendlang']['backendlang']['View']) ? $data['backendlang']['backendlang']['View'] :'' }}</a>
							        		</li>
							        	</ul>
					        		</li>
					        		<li>
					        			<a href="javascript:void(0);" class="permission-control parent" data-id="{{ $select->id }}" data-page="">{{ isset($data['backendlang']['backendlang']['Setting_Home_Page']) ? $data['backendlang']['backendlang']['Setting_Home_Page'] :'' }}</a>
							        	<ul>
							        		<li>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['setting-home-page-list'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="setting-home-page-list">{{ isset($data['backendlang']['backendlang']['View']) ? $data['backendlang']['backendlang']['View'] :'' }}</a>
							        		</li>
							        	</ul>
					        		</li>
					        		<li>
					        			<a href="javascript:void(0);" class="permission-control parent" data-id="{{ $select->id }}" data-page="">{{ isset($data['backendlang']['backendlang']['Flow_Setting']) ? $data['backendlang']['backendlang']['Flow_Setting'] :'' }}</a>
							        	<ul>
							        		<li>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['website-setting-insert'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="website-setting-insert">{{ isset($data['backendlang']['backendlang']['Insert']) ? $data['backendlang']['backendlang']['Insert'] :'' }}</a>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['website-setting-list'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="website-setting-list">{{ isset($data['backendlang']['backendlang']['View']) ? $data['backendlang']['backendlang']['View'] :'' }}</a>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['website-setting-edit'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="website-setting-edit">{{ isset($data['backendlang']['backendlang']['Edit']) ? $data['backendlang']['backendlang']['Edit'] :'' }}</a>
							        			<a href="javascript:void(0);" class="permission-control {{ (isset($get_permission[$select->id]['website-setting-delete'])) ? 'active' : '' }}" data-id="{{ $select->id }}" data-page="website-setting-delete">{{ isset($data['backendlang']['backendlang']['Delete']) ? $data['backendlang']['backendlang']['Delete'] :'' }}</a>
							        		</li>
							        	</ul>
					        		</li>
					        	</ul>
					        </li>
					    </ul>
					</div>
				</div>
			</div>
			@endforeach
	</div>
@endsection

@section('js')
<script type="text/javascript">
	$('.permission-control').click(function(e){
		$('.loading-gif').show();
		var ele = $(this);
		var id = ele.data('id');
		var page = ele.data('page');

		ele.toggleClass('active');

		var fd = new FormData();
			fd.append('page', page);
			fd.append('permission_lvl', id);
		if(page){
			$.ajax({
		       	url: '{{ route("SetPermission") }}',
		       	type: 'post',
		       	data: fd,
		       	contentType: false,
		       	processData: false,
		       	success: function(response){
		       		$('.loading-gif').hide();
		       	}
		   	});			
		}else{
			$('.loading-gif').hide();
		}
	});
</script>
@endsection