<div class="logo_Section_main_sidebar">
    <a href="{{ route('home') }}" class="logo-wrapper">
        @php
            $photo = isset($whiteLabelInfo->mini_logo)
                ? 'uploads/white_label/' . $whiteLabelInfo->mini_logo
                : 'frequent_changing/images/mini_logo.png';
            
            $logo_lg = isset($whiteLabelInfo->logo)
                ? 'uploads/white_label/' . $whiteLabelInfo->logo
                : 'frequent_changing/images/logo.png';
        @endphp

        <span class="logo-lg">
            <img src="{{ $baseURL . $logo_lg }}" class="img-circle" alt="Logo Image">
        </span>
        <span class="logo-mini">
            <img src="{{ $baseURL . $photo }}" class="img-circle" alt="Logo Image">
        </span>
    </a>
    <a href="#" class="sidebar-toggle set_collapse" data-status="2" data-toggle="push-menu" role="button"
        style="transform: rotate(0deg); transition: 0.7s;">
        <iconify-icon icon="solar:round-alt-arrow-left-broken" width="25"></iconify-icon>
    </a>
</div>
<!-- Admin Logo Part End -->
<section class="sidebar">

    <div id="left_menu_to_scroll">
        <ul class="sidebar-menu ps ps--active-x ps--active-y tree" data-widget="tree">

            <li class="parent-menu treeview2 menu_assign_class menu__cidirp_1{{ request()->is('home') ? ' menu-open active_sub_menu' : '' }}"
                data-menu__cid="irp_1">
                <a href="{{ route('home') }}">
                    <iconify-icon icon="solar:home-broken"></iconify-icon>
                    <span class="match_bold">@lang('index.home')</span>
                </a>
            </li>
            @if (menuPermission('Dashboard'))
                <li class="parent-menu treeview2 menu_assign_class menu__cidirp_1{{ request()->is('dashboard') ? ' menu-open active_sub_menu' : '' }}"
                    data-menu__cid="irp_1">
                    <a href="{{ route('dashboard') }}">
                        <iconify-icon icon="solar:chart-2-broken"></iconify-icon>
                        <span class="match_bold">@lang('index.dashboard')</span>
                    </a>
                </li>
            @endif
            @if (menuPermission('Production'))
                <li
                    class="parent-menu treeview menu__cidirp_10{{ request()->is('productions*') || request()->is('production-loss*') ? ' menu-open active_sub_menu' : '' }}">
                    <a href="#">
                        <iconify-icon icon="solar:chart-square-broken"></iconify-icon>
                        <span class="match_bold">@lang('index.manufacture')</span>
                    </a>                    
                    <ul class="treeview-menu">
                        @if (routePermission('manufacture.create'))
                            <li class="menu_assign_class {{ request()->routeIs('productions.create') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('productions.create') }}">@lang('index.add_manufacture')</a>
                            </li>
                        @endif
                        @if (routePermission('manufacture.index'))
                            <li class="menu_assign_class {{ request()->routeIs('productions.index') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('productions.index') }}">@lang('index.manufacture_list')</a>
                            </li>
                        @endif
                        @if (routePermission('production-loss.create'))
                            <li class="menu_assign_class {{ request()->routeIs('production-loss') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('production-loss') }}">@lang('index.add_production_loss')</a>
                            </li>
                        @endif
                        @if (routePermission('production-loss.index'))
                            <li class="menu_assign_class {{ request()->routeIs('production-loss-report') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('production-loss-report') }}">@lang('index.production_loss_list')</a>
                            </li>
                        @endif                       
                        @if (routePermission('demand-forecasting-by-order'))
                            <li class="menu_assign_class {{ request()->routeIs('forecasting.order') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('forecasting.order') }}">@lang('index.demand_forecasting_by_order')</a>
                            </li>
                        @endif
                        @if (routePermission('demand-forecasting-by-product'))
                            <li class="menu_assign_class {{ request()->routeIs('forecasting.product') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('forecasting.product') }}">@lang('index.demand_forecasting_by_product')</a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
            @if (menuPermission('Product Stock'))
                <li class="parent-menu treeview2 menu_assign_class menu__cidirp_1{{ request()->is('product-stock*') ? ' menu-open active_sub_menu' : '' }}"
                    data-menu__cid="irp_1">
                    <a href="{{ route('product-stock') }}">
                        <iconify-icon icon="solar:bag-2-broken"></iconify-icon>
                        <span class="match_bold">@lang('index.product_stocks')</span>
                    </a>
                </li>
            @endif
            @if (menuPermission('Orders'))
                <li
                    class="parent-menu treeview menu__cidirp_10{{ request()->is('customer-orders*') ? ' menu-open active_sub_menu' : '' }}">
                    <a href="#">
                        <iconify-icon icon="solar:user-broken"></iconify-icon>
                        <span class="match_bold">@lang('index.orders')</span>
                    </a>
                    
                    <ul class="treeview-menu">
                        
                        @if (routePermission('order.create'))
                            <li class="menu_assign_class {{ request()->routeIs('customer-orders.create') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('customer-orders.create') }}">@lang('index.add_order')</a>
                            </li>
                        @endif
                        @if (routePermission('order.index'))
                            <li class="menu_assign_class {{ request()->routeIs('customer-orders.index') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('customer-orders.index') }}">@lang('index.order_list')</a>
                            </li>
                        @endif
                        @if (routePermission('order-status'))
                            <li class="menu_assign_class {{ request()->routeIs('customer-order-status') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('customer-order-status') }}">@lang('index.order_status')</a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
            @if (menuPermission('Sales'))
                <li
                    class="parent-menu treeview menu__cidirp_10{{ request()->is('sales*') ? ' menu-open active_sub_menu' : '' }}">
                    <a href="#">
                        <iconify-icon icon="solar:cart-large-broken"></iconify-icon>
                        <span class="match_bold">@lang('index.sale')</span>
                    </a>
                    
                    <ul class="treeview-menu">
                        
                        @if (routePermission('sale.create'))
                            <li class="menu_assign_class {{ request()->routeIs('sales.create') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('sales.create') }}">@lang('index.add_sale')</a>
                            </li>
                        @endif
                        @if (routePermission('sale.index'))
                            <li class="menu_assign_class {{ request()->routeIs('sales.index') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('sales.index') }}">@lang('index.sale_list')</a>
                            </li>
                        @endif
                        
                    </ul>
                </li>
            @endif
            @if (menuPermission('Purchase'))
                <li
                    class="parent-menu treeview menu__cidirp_10{{ request()->is('rawmaterialpurchases*') ? ' menu-open active_sub_menu' : '' }}">
                    <a href="#">
                        <iconify-icon icon="solar:cart-check-broken"></iconify-icon>
                        <span class="match_bold">@lang('index.purchase')</span>
                    </a>
                    
                    <ul class="treeview-menu">                        
                        @if (routePermission('purchase.create'))
                            <li class="menu_assign_class {{ request()->routeIs('rawmaterialpurchases.create') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('rawmaterialpurchases.create') }}">@lang('index.add_purchase')</a>
                            </li>
                        @endif
                        @if (routePermission('purchase.index'))
                            <li class="menu_assign_class {{ request()->routeIs('rawmaterialpurchases.index') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('rawmaterialpurchases.index') }}">@lang('index.list_purchase')</a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
            @if (menuPermission('Parties'))
                <li
                    class="parent-menu treeview menu__cidirp_10{{ request()->is('suppliers*') || request()->is('customers*') ? ' menu-open active_sub_menu' : '' }}">
                    <a href="#">
                        <iconify-icon icon="solar:users-group-two-rounded-broken"></iconify-icon>
                        <span class="match_bold">@lang('index.parties')</span>
                    </a>
                    
                    <ul class="treeview-menu">                        
                        @if (routePermission('customer.create'))
                            <li class="menu_assign_class {{ request()->routeIs('customers.create') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('customers.create') }}">@lang('index.add_customer')</a>
                            </li>
                        @endif
                        @if (routePermission('customer.index'))
                            <li class="menu_assign_class {{ request()->routeIs('customers.index') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('customers.index') }}">@lang('index.list_customer')</a>
                            </li>
                        @endif                        
                        @if (routePermission('supplier.create'))
                            <li class="menu_assign_class {{ request()->routeIs('suppliers.create') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('suppliers.create') }}">@lang('index.add_supplier')</a>
                            </li>
                        @endif
                        @if (routePermission('supplier.index'))
                            <li class="menu_assign_class {{ request()->routeIs('suppliers.index') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('suppliers.index') }}">@lang('index.list_supplier')</a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
            @if (menuPermission('RM Stock'))
                <li class="parent-menu treeview menu_assign_class menu__cidirp_1{{ request()->is('getRMStock*') || request()->is('getLowStock*') || request()->is('stock-adjustment*') ? ' menu-open active_sub_menu' : '' }}"
                    data-menu__cid="irp_1">
                    <a href="#">
                        <iconify-icon icon="solar:database-broken"></iconify-icon>
                        <span class="match_bold">@lang('index.rm_stocks')</span>
                    </a>
                    
                    <ul class="treeview-menu">                        
                        @if (routePermission('rm.stock'))
                            <li class="menu_assign_class {{ request()->routeIs('getRMStock') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('getRMStock') }}">@lang('index.rm_stocks')</a>
                            </li>
                            <li class="menu_assign_class {{ request()->routeIs('getLowStock') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('getLowStock') }}">@lang('index.low_stock')</a>
                            </li>
                        @endif                        
                        @if (routePermission('stock-adjustment.create'))
                            <li class="menu_assign_class {{ request()->routeIs('stockAdjust') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('stockAdjust') }}">@lang('index.add_stock_adjustment')</a>
                            </li>
                        @endif
                        @if (routePermission('stock-adjustment.list'))
                            <li class="menu_assign_class {{ request()->routeIs('stockAdjustList') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('stockAdjustList') }}">@lang('index.stock_adjustment_list')</a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
            @if (menuPermission('Attendance'))
                <li
                    class="parent-menu treeview menu__cidirp_10{{ request()->is('attendance*') ? ' menu-open active_sub_menu' : '' }}">
                    <a href="#">
                        <iconify-icon icon="solar:stopwatch-broken"></iconify-icon>
                        <span class="match_bold">@lang('index.attendance')</span>
                    </a>
                    
                    <ul class="treeview-menu">                        
                        @if (routePermission('attendance.create'))
                            <li class="menu_assign_class {{ request()->routeIs('attendance.create') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('attendance.create') }}">@lang('index.add_attendance')</a>
                            </li>
                        @endif
                        @if (routePermission('attendance.index'))
                            <li class="menu_assign_class {{ request()->routeIs('attendance.index') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('attendance.index') }}">@lang('index.attendance_list')</a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
            @if (menuPermission('Expenses'))
                <li
                    class="parent-menu treeview menu__cidirp_10{{ request()->is('expense') || request()->is('expense/*') || request()->is('expense-category*') ? ' menu-open active_sub_menu' : '' }}">
                    <a href="#">
                        <iconify-icon icon="solar:money-bag-broken"></iconify-icon>
                        <span class="match_bold">@lang('index.expense')</span>
                    </a>
                    
                    <ul class="treeview-menu">                        
                        @if (routePermission('expense.create'))
                            <li class="menu_assign_class {{ request()->routeIs('expense.create') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('expense.create') }}">@lang('index.add_expense')</a>
                            </li>
                        @endif
                        @if (routePermission('expense.index'))
                            <li class="menu_assign_class {{ request()->routeIs('expense.index') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('expense.index') }}">@lang('index.expense_list')</a>
                            </li>
                        @endif                        
                        @if (routePermission('expensecategory.create'))
                            <li class="menu_assign_class {{ request()->routeIs('expense-category.create') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('expense-category.create') }}">@lang('index.add_expense_category')</a>
                            </li>
                        @endif
                        @if (routePermission('expensecategory.index'))
                            <li class="menu_assign_class {{ request()->routeIs('expense-category.index') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('expense-category.index') }}">@lang('index.expense_category_list')</a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
            @if (menuPermission('Accounting'))
                <li
                    class="parent-menu treeview menu__cidirp_10{{ request()->is('accounts*') || request()->is('deposit*') || request()->is('balance-sheet*') || request()->is('trial-balance*') ? ' menu-open active_sub_menu' : '' }}">
                    <a href="#">
                        <iconify-icon icon="solar:wallet-money-broken"></iconify-icon>
                        <span class="match_bold">@lang('index.accounting')</span>
                    </a>
                    
                    <ul class="treeview-menu">                        
                        @if (routePermission('account.create'))
                            <li class="menu_assign_class {{ request()->routeIs('accounts.create') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('accounts.create') }}">@lang('index.add_account')</a>
                            </li>
                        @endif
                        @if (routePermission('account.index'))
                            <li class="menu_assign_class {{ request()->routeIs('accounts.index') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('accounts.index') }}">@lang('index.list_account')</a>
                            </li>
                        @endif                        
                        @if (routePermission('deposit.create'))
                            <li class="menu_assign_class {{ request()->routeIs('deposit.create') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('deposit.create') }}">@lang('index.add_deposit_or_withdraw')</a>
                            </li>
                        @endif
                        @if (routePermission('deposit.index'))
                            <li class="menu_assign_class {{ request()->routeIs('deposit.index') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('deposit.index') }}">@lang('index.list_deposit_or_withdraw')</a>
                            </li>
                        @endif                        
                        @if (routePermission('balancesheet'))
                            <li class="menu_assign_class {{ request()->routeIs('balance-sheet') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('balance-sheet') }}">@lang('index.balance_sheet')</a>
                            </li>
                        @endif
                        @if (routePermission('trialbalance'))
                            <li class="menu_assign_class {{ request()->routeIs('trial-balance') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('trial-balance') }}">@lang('index.trial_balance')</a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
            @if (menuPermission('Supplier Payment'))
                <li
                    class="parent-menu treeview menu__cidirp_10{{ request()->is('supplier-payment*') ? ' menu-open active_sub_menu' : '' }}">
                    <a href="#">
                        <iconify-icon icon="solar:card-send-broken"></iconify-icon>
                        <span class="match_bold">@lang('index.supplier_payment')</span>
                    </a>
                    
                    <ul class="treeview-menu">                        
                        @if (routePermission('sp.create'))
                            <li class="menu_assign_class {{ request()->routeIs('supplier-payment.create') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('supplier-payment.create') }}">@lang('index.add_supplier_payment')</a>
                            </li>
                        @endif
                        @if (routePermission('sp.index'))
                            <li class="menu_assign_class {{ request()->routeIs('supplier-payment.index') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('supplier-payment.index') }}">@lang('index.supplier_payment_list')</a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
            @if (menuPermission('Customer Receives'))
                <li
                    class="parent-menu treeview menu__cidirp_10{{ request()->is('customer-payment*') ? ' menu-open active_sub_menu' : '' }}">
                    <a href="#">
                        <iconify-icon icon="solar:card-recive-broken"></iconify-icon>
                        <span class="match_bold">@lang('index.customer_receive')</span>
                    </a>
                    
                    <ul class="treeview-menu">                        
                        @if (routePermission('cd.create'))
                            <li class="menu_assign_class {{ request()->routeIs('customer-payment.create') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('customer-payment.create') }}">@lang('index.add_customer_receive')</a>
                            </li>
                        @endif
                        @if (routePermission('cd.index'))
                            <li class="menu_assign_class {{ request()->routeIs('customer-payment.index') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('customer-payment.index') }}">@lang('index.customer_receive_list')</a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
            @if (menuPermission('Payroll'))
                <li
                    class="parent-menu treeview menu__cidirp_10{{ request()->is('payroll*') ? ' menu-open active_sub_menu' : '' }}">
                    <a href="#">
                        <iconify-icon icon="solar:transmission-broken"></iconify-icon>
                        <span class="match_bold">@lang('index.payroll')</span>
                    </a>
                    
                    <ul class="treeview-menu">                        
                        @if (routePermission('payroll.create'))
                            <li class="menu_assign_class {{ request()->routeIs('payroll.create') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('payroll.create') }}">@lang('index.add_payroll')</a>
                            </li>
                        @endif
                        @if (routePermission('payroll.index'))
                            <li class="menu_assign_class {{ request()->routeIs('payroll.index') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('payroll.index') }}">@lang('index.list_payroll')</a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
            @if (menuPermission('Item Setup'))
                <li
                    class="parent-menu treeview menu__cidirp_10{{ request()->is('rmcategories*') || request()->is('rawmaterials*') || request()->is('noninventoryitems*') || request()->is('fpcategories*') || request()->is('finishedproducts*') ? ' menu-open active_sub_menu' : '' }}">
                    <a href="#">
                        <iconify-icon icon="solar:inbox-line-broken"></iconify-icon>
                        <span class="match_bold">@lang('index.item_setup')</span>
                    </a>
                    
                    <ul class="treeview-menu">                        
                        @if (routePermission('rmcategory.create'))
                            <li class="menu_assign_class {{ request()->routeIs('rmcategories.create') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('rmcategories.create') }}">@lang('index.add_rm_category')</a>
                            </li>
                        @endif
                        @if (routePermission('rmcategory.index'))
                            <li class="menu_assign_class {{ request()->routeIs('rmcategories.index') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('rmcategories.index') }}">@lang('index.rm_category')</a>
                            </li>
                        @endif
                        @if (routePermission('rm.create'))
                            <li class="menu_assign_class {{ request()->routeIs('rawmaterials.create') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('rawmaterials.create') }}">@lang('index.add_raw_material')</a>
                            </li>
                        @endif
                        @if (routePermission('rm.index'))
                            <li class="menu_assign_class {{ request()->routeIs('rawmaterials.index') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('rawmaterials.index') }}">@lang('index.list_raw_material')</a>
                            </li>
                        @endif                        
                        @if (routePermission('noi.create'))
                            <li class="menu_assign_class {{ request()->routeIs('noninventoryitems.create') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('noninventoryitems.create') }}">@lang('index.add_non_inventory_item')</a>
                            </li>
                        @endif
                        @if (routePermission('noi.index'))
                            <li class="menu_assign_class {{ request()->routeIs('noninventoryitems.index') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('noninventoryitems.index') }}">@lang('index.list_non_inventory_item')</a>
                            </li>
                        @endif                        
                        @if (routePermission('productcategory.create'))
                            <li class="menu_assign_class {{ request()->routeIs('fpcategories.create') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('fpcategories.create') }}">@lang('index.add_product_category')</a>
                            </li>
                        @endif
                        @if (routePermission('productcategory.index'))
                            <li class="menu_assign_class {{ request()->routeIs('fpcategories.index') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('fpcategories.index') }}">@lang('index.list_product_category')</a>
                            </li>
                        @endif
                        @if (routePermission('product.create'))
                            <li class="menu_assign_class {{ request()->routeIs('finishedproducts.create') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('finishedproducts.create') }}">@lang('index.add_product')</a>
                            </li>
                        @endif
                        @if (routePermission('product.index'))
                            <li class="menu_assign_class {{ request()->routeIs('finishedproducts.index') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('finishedproducts.index') }}">@lang('index.list_product')</a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
            @if (menuPermission('RM Wastes'))
                <li
                    class="parent-menu treeview menu__cidirp_10{{ request()->is('rmwastes*') ? ' menu-open active_sub_menu' : '' }}">
                    <a href="#">
                        <iconify-icon icon="solar:trash-bin-minimalistic-broken"></iconify-icon>
                        <span class="match_bold">@lang('index.rm_waste')</span>
                    </a>
                    
                    <ul class="treeview-menu">                        
                        @if (routePermission('rmwaste.create'))
                            <li class="menu_assign_class {{ request()->routeIs('rmwastes.create') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('rmwastes.create') }}">@lang('index.add_rm_waste')</a>
                            </li>
                        @endif
                        @if (routePermission('rmwaste.index'))
                            <li class="menu_assign_class {{ request()->routeIs('rmwastes.index') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('rmwastes.index') }}">@lang('index.list_rm_waste')</a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
            @if (menuPermission('Product Wastes'))
                <li
                    class="parent-menu treeview menu__cidirp_10{{ request()->is('product-wastes*') ? ' menu-open active_sub_menu' : '' }}">
                    <a href="#">
                        <iconify-icon icon="solar:paper-bin-broken"></iconify-icon>
                        <span class="match_bold">@lang('index.product_waste')</span>
                    </a>
                    
                    <ul class="treeview-menu">
                        @if (routePermission('productwaste.create'))
                            <li class="menu_assign_class {{ request()->routeIs('product-wastes.create') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('product-wastes.create') }}">@lang('index.add_product_waste')</a>
                            </li>
                        @endif
                        @if (routePermission('productwaste.index'))
                            <li class="menu_assign_class {{ request()->routeIs('product-wastes.index') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('product-wastes.index') }}">@lang('index.list_product_waste')</a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
            @if (menuPermission('Quotations'))
                <li
                    class="parent-menu treeview menu__cidirp_10{{ request()->is('quotation*') ? ' menu-open active_sub_menu' : '' }}">
                    <a href="#">
                        <iconify-icon icon="solar:ruler-pen-broken"></iconify-icon>
                        <span class="match_bold">@lang('index.quotions')</span>
                    </a>
                    
                    <ul class="treeview-menu">
                        @if (routePermission('quotations.create'))
                            <li class="menu_assign_class {{ request()->routeIs('quotation.create') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('quotation.create') }}">@lang('index.add_quotion')</a>
                            </li>
                        @endif
                        @if (routePermission('quotations.index'))
                            <li class="menu_assign_class {{ request()->routeIs('quotation.index') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('quotation.index') }}">@lang('index.quotion_list')</a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
            @if (menuPermission('Reports'))
                <li
                    class="parent-menu treeview menu__cidirp_10{{ request()->is('rm-purchase-report*') || request()->is('rm-item-purchase-report*') || request()->is('rm-stock-report*') || request()->is('supplier-due-report*') || request()->is('supplier-balance-report*') || request()->is('supplier-ledger*') || request()->is('production-report*') || request()->is('fp-production-report*') || request()->is('fp-sale-report*') || request()->is('fp-item-sale-report*') || request()->is('customer-due-report*') || request()->is('customer-ledger*') || request()->is('profit-loss-report*') || request()->is('product-profit-report*') || request()->is('attendance-report*') || request()->is('expense-report*') || request()->is('salary-report*') || request()->is('rmwaste-report*') || request()->is('fpwaste-report*') || request()->is('rm-price-history') || request()->is('product-price-history') ? ' menu-open active_sub_menu' : '' }}">
                    <a href="#">
                        <iconify-icon icon="solar:diagram-down-broken"></iconify-icon>
                        <span class="match_bold">@lang('index.report')</span>
                    </a>
                    
                    <ul class="treeview-menu">
                        
                        @if (routePermission('product-price-history'))
                            <li class="menu_assign_class {{ request()->routeIs('product.price.history') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('product.price.history') }}">@lang('index.product_price_history')</a>
                            </li>
                        @endif
                        @if (routePermission('rm-price-history'))
                            <li class="menu_assign_class {{ request()->routeIs('price-history') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('price-history') }}">@lang('index.rm_price_history')</a>
                            </li>
                        @endif
                        @if (routePermission('rmpurchase.report'))
                            <li class="menu_assign_class {{ request()->routeIs('rm-purchase-report') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('rm-purchase-report') }}">@lang('index.rm_purchase_report')</a>
                            </li>
                        @endif
                        @if (routePermission('rmpurchaseitem.report'))
                            <li class="menu_assign_class {{ request()->routeIs('rm-item-purchase-report') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('rm-item-purchase-report') }}">@lang('index.rm_item_purchase_report')</a>
                            </li>
                        @endif
                        @if (routePermission('rmstock.report'))
                            <li class="menu_assign_class {{ request()->routeIs('rm-stock-report') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('rm-stock-report') }}">@lang('index.rm_stock_report')</a>
                            </li>
                        @endif
                        @if (routePermission('supplierdue.report'))
                            <li class="menu_assign_class {{ request()->routeIs('supplier-due-report') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('supplier-due-report') }}">@lang('index.supplier_due_report')</a>
                            </li>
                        @endif
                        @if (routePermission('supplierdue.report'))
                            <li class="menu_assign_class {{ request()->routeIs('supplier-balance-report') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('supplier-balance-report') }}">@lang('index.supplier_balance_report')</a>
                            </li>
                        @endif
                        @if (routePermission('supplierledger.report'))
                            <li class="menu_assign_class {{ request()->routeIs('supplier-ledger') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('supplier-ledger') }}">@lang('index.supplier_ledger')</a>
                            </li>
                        @endif
                        @if (routePermission('production.report'))
                            <li class="menu_assign_class {{ request()->routeIs('production-report') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('production-report') }}">@lang('index.production_report')</a>
                            </li>
                        @endif
                        @if (routePermission('fpp.report'))
                            <li class="menu_assign_class {{ request()->routeIs('fp-production-report') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('fp-production-report') }}">@lang('index.fp_production_report')</a>
                            </li>
                        @endif
                        @if (routePermission('fpsale.report'))
                            <li class="menu_assign_class {{ request()->routeIs('fp-sale-report') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('fp-sale-report') }}">@lang('index.fp_sale_report')</a>
                            </li>
                        @endif
                        @if (routePermission('fpitemsale.report'))
                            <li class="menu_assign_class {{ request()->routeIs('fp-item-sale-report') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('fp-item-sale-report') }}">@lang('index.fp_item_sale_report')</a>
                            </li>
                        @endif
                        @if (routePermission('customerdue.report'))
                            <li class="menu_assign_class {{ request()->routeIs('customer-due-report') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('customer-due-report') }}">@lang('index.customer_due_report')</a>
                            </li>
                        @endif
                        @if (routePermission('customerledger'))
                            <li class="menu_assign_class {{ request()->routeIs('customer-ledger') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('customer-ledger') }}">@lang('index.customer_ledger')</a>
                            </li>
                        @endif
                        @if (routePermission('profit-loss'))
                            <li class="menu_assign_class {{ request()->routeIs('profit-loss-report') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('profit-loss-report') }}">@lang('index.profit_loss_report')</a>
                            </li>
                        @endif
                        @if (routePermission('production-profit.report'))
                            <li class="menu_assign_class {{ request()->routeIs('product-profit-report') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('product-profit-report') }}">@lang('index.product_profit_report')</a>
                            </li>
                        @endif
                        @if (routePermission('attandance.report'))
                            <li class="menu_assign_class {{ request()->routeIs('attendance-report') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('attendance-report') }}">@lang('index.attendance_report')</a>
                            </li>
                        @endif
                        @if (routePermission('expense-report'))
                            <li class="menu_assign_class {{ request()->routeIs('expense-report') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('expense-report') }}">@lang('index.expense_report')</a>
                            </li>
                        @endif
                        @if (routePermission('salary-report'))
                            <li class="menu_assign_class {{ request()->routeIs('salary-report') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('salary-report') }}">@lang('index.salary_report')</a>
                            </li>
                        @endif
                        @if (routePermission('rmwaste-report'))
                            <li class="menu_assign_class {{ request()->routeIs('rmwaste-report') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('rmwaste-report') }}">@lang('index.rmwaste_report')</a>
                            </li>
                        @endif
                        @if (routePermission('productwaste-report'))
                            <li class="menu_assign_class {{ request()->routeIs('fpwaste-report') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('fpwaste-report') }}">@lang('index.product_waste_report')</a>
                            </li>
                        @endif
                        @if (routePermission('abcanalysis-report'))
                            <li class="menu_assign_class {{ request()->routeIs('abc-analysis-report') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('abc-analysis-report') }}">@lang('index.abc_analysis_report')</a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
            @if (menuPermission('Users'))
                <li
                    class="parent-menu treeview menu__cidirp_10{{ request()->is('user*') || request()->is('role*') ? ' menu-open active_sub_menu' : '' }}">
                    <a href="#">
                        <iconify-icon icon="solar:user-circle-broken"></iconify-icon>
                        <span class="match_bold">@lang('index.user')</span>
                    </a>
                    
                    <ul class="treeview-menu">                        
                        @if (routePermission('role.create'))
                            <li class="menu_assign_class {{ request()->routeIs('role.create') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('role.create') }}">@lang('index.add_role')</a>
                            </li>
                        @endif
                        @if (routePermission('role.index'))
                            <li class="menu_assign_class {{ request()->routeIs('role.index') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('role.index') }}">@lang('index.list_role')</a>
                            </li>
                        @endif                        
                        @if (routePermission('user.create'))
                            <li class="menu_assign_class {{ request()->routeIs('user.create') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('user.create') }}">@lang('index.add_user')</a>
                            </li>
                        @endif
                        @if (routePermission('user.index'))
                            <li class="menu_assign_class {{ request()->routeIs('user.index') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('user.index') }}">@lang('index.list_user')</a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
            @if (menuPermission('Settings'))
                <li
                    class="parent-menu treeview menu__cidirp_10{{ request()->is('settings') || request()->is('white-label') || request()->is('taxes') || request()->is('units*') || request()->is('mail-settings') || request()->is('productionstages*') || request()->is('currency*') ? ' menu-open active_sub_menu' : '' }}">
                    <a href="#">
                        <iconify-icon icon="solar:settings-broken"></iconify-icon>
                        <span class="match_bold">@lang('index.settings')</span>
                    </a>
                    
                    <ul class="treeview-menu">                        
                        @if (routePermission('settings'))
                            <li class="menu_assign_class {{ request()->routeIs('settings') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('settings') }}">                                    
                                    @lang('index.company_profile')
                                </a>
                            </li>
                        @endif
                        @if (routePermission('taxes'))
                            <li class="menu_assign_class {{ request()->routeIs('taxes') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('taxes') }}">@lang('index.tax_settings')</a>
                            </li>
                        @endif
                        @if (isWhiteLabelChangeAble())
                            @if (routePermission('white-label'))
                                <li class="menu_assign_class {{ request()->routeIs('white-label') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                        href="{{ route('white-label') }}">@lang('index.white_label')</a>
                                </li>
                            @endif
                        @endif
                        @if (routePermission('mail-settings'))
                            <li class="menu_assign_class {{ request()->routeIs('settings.mail.index') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('settings.mail.index') }}">@lang('index.mail_settings')</a>
                            </li>
                        @endif
                        @if (routePermission('productionstage.list'))
                            <li class="menu_assign_class {{ request()->routeIs('data-import') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('data-import') }}">@lang('index.data_import')</a>
                            </li>
                        @endif                        
                        @if (routePermission('units.create'))
                            <li class="menu_assign_class {{ request()->routeIs('units.create') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('units.create') }}">@lang('index.add_unit')</a>
                            </li>
                        @endif
                        @if (routePermission('currency.index'))
                            <li class="menu_assign_class {{ request()->routeIs('currency.index') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('currency.index') }}">@lang('index.list_currency')</a>
                            </li>
                        @endif    
                        @if (routePermission('currency.create'))
                            <li class="menu_assign_class {{ request()->routeIs('currency.create') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('currency.create') }}">@lang('index.add_currency')</a>
                            </li>
                        @endif
                        @if (routePermission('units.index'))
                            <li class="menu_assign_class {{ request()->routeIs('units.index') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('units.index') }}">@lang('index.list_unit')</a>
                            </li>
                        @endif                      
                        @if (routePermission('productionstage.create'))
                            <li class="menu_assign_class {{ request()->routeIs('productionstages.create') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('productionstages.create') }}">@lang('index.add_production_stage')</a>
                            </li>
                        @endif
                        @if (routePermission('productionstage.list'))
                            <li class="menu_assign_class {{ request()->routeIs('productionstages.index') ? ' treeMenuActive' : '' }}" data-menu__cid="irp_10"><a
                                    href="{{ route('productionstages.index') }}">@lang('index.list_production_stage')</a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
            <div class="ps__rail-x">
                <div class="ps__thumb-x" tabindex="0"></div>
            </div>
            <div class="ps__rail-y">
                <div class="ps__thumb-y" tabindex="0"></div>
            </div>
        </ul>
    </div>
</section>
