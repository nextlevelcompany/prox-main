<?php

use Modules\Company\Models\Configuration;
use Modules\Inventory\Models\InventoryConfiguration;

$configuration = Configuration::first();
$firstLevel = $path[0] ?? null;
$secondLevel = $path[1] ?? null;
$thridLevel = $path[2] ?? null;

$inventory_configuration = InventoryConfiguration::getSidebarPermissions();

$path_logo = ($vc_company->logo) ? 'storage/uploads/logos/'.$vc_company->logo : 'logo/tulogo.png'

?>
<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="/" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ URL::asset($path_logo) }}" alt="" height="35">
            </span>
            <span class="logo-lg">
                <img src="{{ URL::asset($path_logo) }}" alt="" height="50">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="/" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ URL::asset($path_logo) }}" alt="" height="35">
            </span>
            <span class="logo-lg">
                <img src="{{ URL::asset($path_logo) }}" alt="" height="50">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span>Menu</span></li>
                @if(in_array('dashboard', $vc_modules))
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ ($firstLevel === 'dashboard')?'active':'' }}" href="{{ route('tenant.dashboard.index') }}">
                            <i class="ri-dashboard-2-line"></i> <span>Dashboard</span>
                        </a>
                    </li>
                @endif
                {{-- Ventas --}}
                @if(in_array('documents', $vc_modules))
                    <li class="nav-item">
                        @php
                            $menu_documents = ['documents','summaries','voided','quotations','sale-notes','contingencies','incentives','order-notes','sale-opportunities','contracts','production-orders','technical-services','user-commissions','regularize-shipping'
                            ];
                        @endphp
                        <a
                            class="nav-link menu-link {{ in_array($firstLevel, $menu_documents) ? 'active' : '' }}"
                            href="#sidebarDocuments"
                            data-bs-toggle="collapse"
                            role="button"
                            aria-expanded="{{ in_array($firstLevel, $menu_documents) ? 'true' : 'false' }}"
                            aria-controls="sidebarDocuments">
                            <i class="ri-database-line"></i> <span>Ventas</span>
                        </a>
                        <div class="collapse menu-dropdown {{ in_array($firstLevel, $menu_documents) ? 'show' : '' }}" id="sidebarDocuments">
                            <ul class="nav nav-sm flex-column">
                                @if(auth()->user()->type != 'integrator' && $vc_company->soap_type_id != '03')
                                    @if(in_array('documents', $vc_modules))
                                        @if(in_array('new_document', $vc_module_levels))
                                            <li class="nav-item">
                                                <a
                                                    href="{{route('tenant.documents.create')}}"
                                                    class="nav-link {{ ($firstLevel === 'documents' && $secondLevel === 'create')?'active':'' }}">
                                                    Comprobante electrónico
                                                </a>
                                            </li>
                                        @endif
                                    @endif
                                @endif
                                @if(in_array('documents', $vc_modules) && $vc_company->soap_type_id != '03')
                                    @if(in_array('list_document', $vc_module_levels))
                                        <li class="nav-item">
                                            <a
                                                href="{{route('tenant.documents.index')}}"
                                                class="nav-link {{ ($firstLevel === 'documents' && $secondLevel != 'create' && $secondLevel != 'not-sent'&& $secondLevel != 'regularize-shipping')?'active':'' }}">
                                                Listado de comprobantes
                                            </a>
                                        </li>
                                    @endif
                                @endif
                                @if(in_array('sale_notes', $vc_module_levels))
                                    <li class="nav-item">
                                        <a
                                            href="{{route('tenant.sale_notes.index')}}"
                                            class="nav-link {{ ($firstLevel === 'sale-notes')?'active':'' }}">
                                            Notas de Venta
                                        </a>
                                    </li>
                                @endif
                                @if(in_array('documents', $vc_modules) && $vc_company->soap_type_id != '03')
                                    @if(in_array('document_not_sent', $vc_module_levels))
                                        <li class="nav-item">
                                            <a
                                                href="{{route('tenant.documents.not_sent')}}"
                                                class="nav-link {{ ($firstLevel === 'documents' && $secondLevel === 'not-sent')?'active':'' }}">
                                                CPE no enviados
                                            </a>
                                        </li>
                                    @endif
                                    @if(in_array('regularize_shipping', $vc_module_levels))
                                        <li class="nav-item">
                                            <a
                                                href="{{route('tenant.documents.regularize_shipping')}}"
                                                class="nav-link {{ ($firstLevel === 'documents' && $secondLevel === 'regularize-shipping')?'active':'' }}">
                                                CPE pendientes de rectificación
                                            </a>
                                        </li>
                                    @endif
                                @endif
                                @if(auth()->user()->type != 'integrator' && in_array('documents', $vc_modules) )
                                    @if(auth()->user()->type != 'integrator' && in_array('document_contingengy', $vc_module_levels) && $vc_company->soap_type_id != '03')
                                        <li class="nav-item">
                                            <a
                                                href="{{route('tenant.contingencies.index')}}"
                                                class="nav-link {{ ($firstLevel === 'contingencies' )?'active':'' }}">
                                                Documentos de contingencia
                                            </a>
                                        </li>
                                    @endif
                                    @if(in_array('summary_voided', $vc_module_levels) && $vc_company->soap_type_id != '03')
                                        <li class="nav-item">
                                            <a
                                                href="#sidebarSummaries"
                                                class="nav-link {{ ($firstLevel === 'summaries' || $firstLevel === 'voided')?'active':'' }}"
                                                data-bs-toggle="collapse"
                                                role="button"
                                                aria-expanded="{{ ($firstLevel === 'summaries' || $firstLevel === 'voided')?'true':'false' }}"
                                                aria-controls="sidebarSummaries">
                                                Resúmenes y Anulaciones
                                            </a>
                                            <div class="collapse menu-dropdown {{ ($firstLevel === 'summaries' || $firstLevel === 'voided')?'show':'' }}" id="sidebarSummaries">
                                                <ul class="nav nav-sm flex-column">
                                                    <li class="nav-item">
                                                        <a
                                                            href="{{route('tenant.summaries.index')}}"
                                                            class="nav-link {{ ($firstLevel === 'summaries')?'active':'' }}">
                                                            Resúmenes
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a
                                                            href="{{route('tenant.voided.index')}}"
                                                            class="nav-link {{ ($firstLevel === 'voided')?'active':'' }}">
                                                            Anulaciones
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </li>
                                    @endif
                                    @if(in_array('sale-opportunity', $vc_module_levels))
                                        <li class="nav-item">
                                            <a
                                                href="{{route('tenant.sale_opportunities.index')}}"
                                                class="nav-link {{ ($firstLevel === 'sale-opportunities')?'active':'' }}">
                                                Oportunidad de venta
                                            </a>
                                        </li>
                                    @endif
                                    @if(in_array('quotations', $vc_module_levels))
                                        <li class="nav-item">
                                            <a
                                                href="{{route('tenant.quotations.index')}}"
                                                class="nav-link {{ ($firstLevel === 'quotations')?'active':'' }}">
                                                Cotizaciones
                                            </a>
                                        </li>
                                    @endif
                                    @if(in_array('contracts', $vc_module_levels))
                                        <li class="nav-item">
                                            <a
                                                href="#sidebarContracts"
                                                class="nav-link {{ ($firstLevel === 'contracts' || $firstLevel === 'production-orders')?'active':'' }}"
                                                data-bs-toggle="collapse"
                                                role="button"
                                                aria-expanded="{{ ($firstLevel === 'contracts' || $firstLevel === 'production-orders')?'true':'false' }}"
                                                aria-controls="sidebarContracts">
                                                Contratos
                                            </a>
                                            <div class="collapse menu-dropdown {{ ($firstLevel === 'contracts' || $firstLevel === 'production-orders')?'show':'' }}" id="sidebarContracts">
                                                <ul class="nav nav-sm flex-column">
                                                    <li class="nav-item">
                                                        <a
                                                            href="{{route('tenant.contracts.index')}}"
                                                            class="nav-link {{ ($firstLevel === 'contracts')?'active':'' }}">
                                                            Listado
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a
                                                            href="{{route('tenant.production_orders.index')}}"
                                                            class="nav-link {{ ($firstLevel === 'production-orders')?'active':'' }}">
                                                            Ordenes de Producción
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </li>
                                    @endif
                                    @if(in_array('order-note', $vc_module_levels))
                                        <li class="nav-item">
                                            <a
                                                href="{{route('tenant.order_notes.index')}}"
                                                class="nav-link {{ ($firstLevel === 'order-notes')?'active':'' }}">
                                                Pedidos
                                            </a>
                                        </li>
                                    @endif
                                    @if(in_array('technical-service', $vc_module_levels))
                                        <li class="nav-item">
                                            <a
                                                href="{{route('tenant.technical_services.index')}}"
                                                class="nav-link {{ ($firstLevel === 'technical-services')?'active':'' }}">
                                                Servicio de soporte técnico
                                            </a>
                                        </li>
                                    @endif
                                    @if(in_array('incentives', $vc_module_levels))
                                        <li class="nav-item">
                                            <a
                                                href="#sidebarInsentives"
                                                class="nav-link {{ ($firstLevel === 'incentives' || $firstLevel === 'user-commissions')?'active':'' }}"
                                                data-bs-toggle="collapse"
                                                role="button"
                                                aria-expanded="{{ ($firstLevel === 'incentives' || $firstLevel === 'user-commissions')?'true':'false' }}"
                                                aria-controls="sidebarInsentives">
                                                Comisiones
                                            </a>
                                            <div class="collapse menu-dropdown {{ ($firstLevel === 'incentives' || $firstLevel === 'user-commissions')?'show':'' }}" id="sidebarInsentives">
                                                <ul class="nav nav-sm flex-column">
                                                    <li class="nav-item">
                                                        <a
                                                            href="{{route('tenant.user_commissions.index')}}"
                                                            class="nav-link {{ ($firstLevel === 'user-commissions')?'active':'' }}">
                                                            Vendedores
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a
                                                            href="{{route('tenant.incentives.index')}}"
                                                            class="nav-link {{ ($firstLevel === 'incentives')?'active':'' }}">
                                                            Productos
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </li>
                                    @endif

                                @endif
                            </ul>
                        </div>
                    </li> <!-- end Dashboard Menu -->
                @endif

                {{-- POS --}}
                @if(auth()->user()->type != 'integrator')
                    @if(in_array('pos', $vc_modules))
                        <li class="nav-item">
                            <a
                                class="nav-link menu-link {{ ($firstLevel === 'pos' || $firstLevel === 'cash')?'active':'' }}"
                                href="#sidebarPos"
                                data-bs-toggle="collapse"
                                role="button"
                                aria-expanded="{{ ($firstLevel === 'pos' || $firstLevel === 'cash') ? 'true' : 'false' }}"
                                aria-controls="sidebarPos">
                                <i class="ri-computer-line"></i> <span>POS</span>
                            </a>
                            <div class="collapse menu-dropdown {{ ($firstLevel === 'pos' || $firstLevel === 'cash') ? 'show' : '' }}" id="sidebarPos">
                                <ul class="nav nav-sm flex-column">
                                    @if(in_array('pos', $vc_module_levels))
                                        <li class="nav-item">
                                            <a
                                                href="{{route('tenant.pos.index')}}"
                                                class="nav-link {{ ($firstLevel === 'pos' && !$secondLevel )?'active':'' }}">
                                                Punto de venta
                                            </a>
                                        </li>
                                    @endif
                                    @if(in_array('pos_garage', $vc_module_levels))
                                        <li class="nav-item">
                                            <a
                                                href="{{route('tenant.pos.garage')}}"
                                                class="nav-link {{ ($firstLevel === 'pos' && $secondLevel === 'garage')?'active':'' }}">
                                                Venta rápida <small>(Grifos y Markets)</small>
                                            </a>
                                        </li>
                                    @endif
                                    @if(in_array('cash', $vc_module_levels))
                                        <li class="nav-item">
                                            <a
                                                href="{{route('tenant.cash.index')}}"
                                                class="nav-link {{ ($firstLevel === 'cash')?'active':'' }}">
                                                Caja chica
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </li>
                    @endif
                @endif

                {{-- Tienda virtual --}}
                @if(in_array('ecommerce', $vc_modules))
                    <li class="nav-item">
                        <a
                            class="nav-link menu-link {{ in_array($firstLevel, ['ecommerce','items_ecommerce', 'tags', 'promotions', 'orders', 'configuration'])?'active':'' }}"
                            href="#sidebarEcommerce"
                            data-bs-toggle="collapse"
                            role="button"
                            aria-expanded="{{ in_array($firstLevel, ['ecommerce','items_ecommerce', 'tags', 'promotions', 'orders', 'configuration'])?'true':'false' }}"
                            aria-controls="sidebarEcommerce">
                            <i class="ri-store-2-fill"></i> <span>Tienda Virtual</span>
                        </a>
                        <div class="collapse menu-dropdown {{ in_array($firstLevel, ['ecommerce','items_ecommerce', 'tags', 'promotions', 'orders', 'configuration'])?'show':'' }}" id="sidebarEcommerce">
                            <ul class="nav nav-sm flex-column">
                                @if(in_array('ecommerce', $vc_module_levels))
                                    <li class="nav-item">
                                        <a
                                            href="{{ route("tenant.ecommerce.index") }}"
                                            target="_blank"
                                            class="nav-link">
                                            Ir a Tienda
                                        </a>
                                    </li>
                                @endif
                                @if(in_array('ecommerce_orders', $vc_module_levels))
                                    <li class="nav-item">
                                        <a
                                            href="{{ route("tenant_orders_index") }}"
                                            class="nav-link {{ ($firstLevel === 'orders')?'active':'' }}">
                                            Pedidos
                                        </a>
                                    </li>
                                @endif
                                @if(in_array('ecommerce_items', $vc_module_levels))
                                    <li class="nav-item">
                                        <a
                                            href="{{ route("tenant.items_ecommerce.index") }}"
                                            class="nav-link {{ ($firstLevel === 'items_ecommerce')?'active':'' }}">
                                            Productos
                                        </a>
                                    </li>
                                @endif
                                {{-- sin module_level --}}
                                <li class="nav-item">
                                    <a
                                        href="{{ route("tenant.ecommerce.item_sets.index") }}"
                                        class="nav-link {{ ($secondLevel === 'item-sets')?'active':'' }}">
                                        Productos compuestos
                                    </a>
                                </li>
                                @if(in_array('ecommerce_tags', $vc_module_levels))
                                    <li class="nav-item">
                                        <a
                                            href="{{ route("tenant.tags.index") }}"
                                            class="nav-link {{ ($firstLevel === 'tags')?'active':'' }}">
                                            Tags (Etiquetas)
                                        </a>
                                    </li>
                                @endif
                                @if(in_array('ecommerce_promotions', $vc_module_levels))
                                    <li class="nav-item">
                                        <a
                                            href="{{ route("tenant.promotion.index") }}"
                                            class="nav-link {{ ($firstLevel === 'promotions')?'active':'' }}">
                                            Banners
                                        </a>
                                    </li>
                                @endif
                                @if(in_array('ecommerce_settings', $vc_module_levels))
                                    <li class="nav-item">
                                        <a
                                            href="{{ route("tenant_ecommerce_configuration") }}"
                                            class="nav-link {{ ($secondLevel === 'configuration')?'active':'' }}">
                                            Configuración
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </li>
                @endif

                {{-- <li class="menu-title"><i class="ri-more-fill"></i> <span>@lang('translation.pages')</span></li> --}}

                {{-- Productos --}}
                @if(in_array('items', $vc_modules))
                    <li class="nav-item">
                        <a
                            class="nav-link menu-link {{ in_array($firstLevel, ['items','services','categories','brands','item-lots','item-sets','zones'])?'active':'' }}"
                            href="#sidebarItems"
                            data-bs-toggle="collapse"
                            role="button"
                            aria-expanded="{{ in_array($firstLevel, ['items','services','categories','brands','item-lots','item-sets','zones'])?'true':'false' }}"
                            aria-controls="sidebarItems">
                            <i class="ri-barcode-box-line"></i> <span>Productos/Servicios</span>
                        </a>
                        <div class="collapse menu-dropdown {{ in_array($firstLevel, ['items','services','categories','brands','item-lots','item-sets','zones'])?'show':'' }}" id="sidebarItems">
                            <ul class="nav nav-sm flex-column">
                                @if(in_array('items', $vc_module_levels))
                                    <li class="nav-item">
                                        <a
                                            href="{{ route("tenant.items.index") }}"
                                            class="nav-link {{ ($firstLevel === 'items')?'active':'' }}">
                                            Productos
                                        </a>
                                    </li>
                                @endif
                                @if(in_array('items_packs', $vc_module_levels))
                                    <li class="nav-item">
                                        <a
                                            href="{{ route("tenant.item_sets.index") }}"
                                            class="nav-link {{ ($firstLevel === 'item-sets')?'active':'' }}">
                                            Conjuntos/Packs/Promociones
                                        </a>
                                    </li>
                                @endif
                                @if(in_array('items_services', $vc_module_levels))
                                    <li class="nav-item">
                                        <a
                                            href="{{ route("tenant.services") }}"
                                            class="nav-link {{ ($firstLevel === 'services')?'active':'' }}">
                                            Servicios
                                        </a>
                                    </li>
                                @endif
                                @if(in_array('items_categories', $vc_module_levels))
                                    <li class="nav-item">
                                        <a
                                            href="{{ route("tenant.categories.index") }}"
                                            class="nav-link {{ ($firstLevel === 'categories')?'active':'' }}">
                                            Categorías
                                        </a>
                                    </li>
                                @endif
                                @if(in_array('items_brands', $vc_module_levels))
                                    <li class="nav-item">
                                        <a
                                            href="{{ route("tenant.brands.index") }}"
                                            class="nav-link {{ ($firstLevel === 'brands')?'active':'' }}">
                                            Marcas
                                        </a>
                                    </li>
                                @endif
                                @if(in_array('items_lots', $vc_module_levels))
                                    <li class="nav-item">
                                        <a
                                            href="{{ route("tenant.item-lots.index") }}"
                                            class="nav-link {{ ($firstLevel === 'item-lots')?'active':'' }}">
                                            Series
                                        </a>
                                    </li>
                                @endif
                                <li class="nav-item">
                                    <a
                                        href="{{ route("tenant.zone.index") }}"
                                        class="nav-link {{ ($firstLevel === 'zones')?'active':'' }}">
                                        Zonas
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif

                {{-- Clientes --}}
                @if(in_array('items', $vc_modules))
                    <li class="nav-item">
                        <a
                            class="nav-link menu-link {{ in_array($firstLevel, ['person-types','agents']) || $firstLevel === 'persons' && $secondLevel === 'customers'?'active':'' }}"
                            href="#sidebarClients"
                            data-bs-toggle="collapse"
                            role="button"
                            aria-expanded="{{ in_array($firstLevel, ['person-types','agents']) || $firstLevel === 'persons' && $secondLevel === 'customers'?'true':'false' }}"
                            aria-controls="sidebarClients">
                            <i class="ri-account-circle-line"></i> <span>Clientes</span>
                        </a>
                        <div class="collapse menu-dropdown {{ in_array($firstLevel, ['person-types','agents']) || $firstLevel === 'persons' && $secondLevel === 'customers'?'show':'' }}" id="sidebarClients">
                            <ul class="nav nav-sm flex-column">
                                @if(in_array('clients', $vc_module_levels))
                                    <li class="nav-item">
                                        <a
                                            href="{{route('tenant.persons.index', ['type' => 'customers'])}}"
                                            class="nav-link {{ ($firstLevel === 'persons' && $secondLevel === 'customers')?'active':'' }}">
                                            Clientes
                                        </a>
                                    </li>
                                @endif
                                @if(in_array('clients_types', $vc_module_levels))
                                    <li class="nav-item">
                                        <a
                                            href="{{route('tenant.person_types.index')}}"
                                            class="nav-link {{ ($firstLevel === 'person-types')?'active':'' }}">
                                            Tipos de clientes
                                        </a>
                                    </li>
                                @endif
                                @if($configuration->enabled_sales_agents)
                                    <li class="nav-item">
                                        <a
                                            href="{{ route("tenant.agents.index") }}"
                                            class="nav-link {{ ($firstLevel === 'agents')?'active':'' }}">
                                            Agentes
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </li>
                @endif

                @if(auth()->user()->type != 'integrator')
                    {{-- Compras --}}
                    @if(in_array('purchases', $vc_modules))
                        <li class="nav-item">
                            <a
                                class="nav-link menu-link {{ in_array($firstLevel, ['purchases','expenses','bank_loan','purchase-quotations','purchase-orders','fixed-asset']) || $secondLevel === 'suppliers' ?'active':'' }}"
                                href="#sidebarPurchases"
                                data-bs-toggle="collapse"
                                role="button"
                                aria-expanded="{{ in_array($firstLevel, ['purchases','expenses','bank_loan','purchase-quotations','purchase-orders','fixed-asset']) || $secondLevel === 'suppliers'?'true':'false' }}"
                                aria-controls="sidebarPurchases">
                                <i class="ri-shopping-bag-line"></i> <span>Compras</span>
                            </a>
                            <div class="collapse menu-dropdown {{ in_array($firstLevel, ['purchases','expenses','bank_loan','purchase-quotations','purchase-orders','fixed-asset']) || $secondLevel === 'suppliers'?'show':'' }}" id="sidebarPurchases">
                                <ul class="nav nav-sm flex-column">
                                    @if(in_array('purchases_create', $vc_module_levels))
                                        <li class="nav-item">
                                            <a
                                                href="{{route('tenant.purchases.create')}}"
                                                class="nav-link {{ ($firstLevel === 'purchases' && $secondLevel === 'create')?'active':'' }}">
                                                Nuevo
                                            </a>
                                        </li>
                                    @endif
                                    @if(in_array('purchases_list', $vc_module_levels))
                                        <li class="nav-item">
                                            <a
                                                href="{{route('tenant.purchases.index')}}"
                                                class="nav-link {{ ($firstLevel === 'purchases' && $secondLevel != 'create')?'active':'' }}">
                                                Listado
                                            </a>
                                        </li>
                                    @endif
                                    @if(in_array('purchases_orders', $vc_module_levels))
                                        <li class="nav-item">
                                            <a
                                                href="{{ route("tenant.purchase-orders.index") }}"
                                                class="nav-link {{ ($firstLevel === 'purchase-orders')?'active':'' }}">
                                                Ordenes de compra
                                            </a>
                                        </li>
                                    @endif
                                    @if(in_array('purchases_expenses', $vc_module_levels))
                                        <li class="nav-item">
                                            <a
                                                href="{{ route("tenant.bank_loan.index") }}"
                                                class="nav-link {{ ($firstLevel === 'bank_loan')?'active':'' }}">
                                                Credito Bancario
                                            </a>
                                        </li>
                                    @endif
                                    @if(in_array('purchases_expenses', $vc_module_levels))
                                        <li class="nav-item">
                                            <a
                                                href="{{ route("tenant.expenses.index") }}"
                                                class="nav-link {{ ($firstLevel === 'expenses')?'active':'' }}">
                                                Gastos diversos
                                            </a>
                                        </li>
                                    @endif
                                    @if(in_array('purchases_quotations', $vc_module_levels) || in_array('purchases_suppliers', $vc_module_levels))
                                        <li class="nav-item">
                                            <a
                                                href="#sidebarSuppliers"
                                                class="nav-link
                                                {{ ($firstLevel === 'persons' && $secondLevel === 'suppliers')?'active':'' }}
                                                {{ ($firstLevel === 'purchase-quotations')?'active':'' }}"
                                                data-bs-toggle="collapse"
                                                role="button"
                                                aria-expanded="{{ ($firstLevel === 'persons' && $secondLevel === 'suppliers')?'true':'false' }}
                                                {{ ($firstLevel === 'purchase-quotations')?'true':'false' }}"
                                                aria-controls="sidebarSuppliers">
                                                Proveedores
                                            </a>
                                            <div class="collapse menu-dropdown {{ ($firstLevel === 'persons' && $secondLevel === 'suppliers')?'show':'' }}
                                            {{ ($firstLevel === 'purchase-quotations')?'show':'' }}" id="sidebarSuppliers">
                                                <ul class="nav nav-sm flex-column">
                                                    @if(in_array('purchases_suppliers', $vc_module_levels))
                                                        <li class="nav-item">
                                                            <a
                                                                href="{{route('tenant.persons.index', ['type' => 'suppliers'])}}"
                                                                class="nav-link {{ ($firstLevel === 'persons' && $secondLevel === 'suppliers')?'active':'' }}">
                                                                Listado
                                                            </a>
                                                        </li>
                                                    @endif
                                                    @if(in_array('purchases_quotations', $vc_module_levels))
                                                        <li class="nav-item">
                                                            <a
                                                                href="{{route('tenant.purchase-quotations.index')}}"
                                                                class="nav-link {{ ($firstLevel === 'purchase-quotations')?'active':'' }}">
                                                                Solicitar cotización
                                                            </a>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </li>
                                    @endif
                                    @if(in_array('purchases_fixed_assets_purchases', $vc_module_levels) || in_array('purchases_fixed_assets_items', $vc_module_levels))
                                        <li class="nav-item">
                                            <a
                                                href="#sidebarFixedAssets"
                                                class="nav-link {{ ($firstLevel === 'fixed-asset' )?'active' : '' }}"
                                                data-bs-toggle="collapse"
                                                role="button"
                                                aria-expanded="{{ ($firstLevel === 'fixed-asset' )?'true' : 'false' }}"
                                                aria-controls="sidebarFixedAssets">
                                                Activos fijos
                                            </a>
                                            <div class="collapse menu-dropdown {{ ($firstLevel === 'fixed-asset' )?'show' : '' }}
                                            {{ ($firstLevel === 'purchase-quotations')?'show':'' }}" id="sidebarFixedAssets">
                                                <ul class="nav nav-sm flex-column">
                                                    @if(in_array('purchases_fixed_assets_items', $vc_module_levels))
                                                        <li class="nav-item">
                                                            <a
                                                                href="{{route('tenant.fixed_asset_items.index')}}"
                                                                class="nav-link {{ ($firstLevel === 'fixed-asset' && $secondLevel === 'items')?'active':'' }}">
                                                                Ítems
                                                            </a>
                                                        </li>
                                                    @endif
                                                    @if(in_array('purchases_fixed_assets_purchases', $vc_module_levels))
                                                        <li class="nav-item">
                                                            <a
                                                                href="{{route('tenant.fixed_asset_purchases.index')}}"
                                                                class="nav-link {{ ($firstLevel === 'fixed-asset' && $secondLevel === 'purchases')?'active':'' }}">
                                                                Compras
                                                            </a>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </li>
                    @endif

                    {{-- Inventario --}}
                    @if(in_array('inventory', $vc_modules))
                        <li class="nav-item">
                            <a
                                class="nav-link menu-link {{ (in_array($firstLevel, ['inventory', 'moves', 'transfers', 'devolutions', 'extra_info_items', 'inventory-review']) || ($firstLevel === 'reports' && in_array($secondLevel, ['kardex', 'inventory', 'valued-kardex'])))?'active':'' }}"
                                href="#sidebarInventory"
                                data-bs-toggle="collapse"
                                role="button"
                                aria-expanded="{{ (in_array($firstLevel, ['inventory', 'moves', 'transfers', 'devolutions', 'extra_info_items', 'inventory-review']) || ($firstLevel === 'reports' && in_array($secondLevel, ['kardex', 'inventory', 'valued-kardex'])))?'true':'false' }}"
                                aria-controls="sidebarInventory">
                                <i class="ri-luggage-cart-fill"></i> <span>Inventario</span>
                            </a>
                            <div class="collapse menu-dropdown {{ (in_array($firstLevel, ['inventory', 'moves', 'transfers', 'devolutions', 'extra_info_items', 'inventory-review']) || ($firstLevel === 'reports' && in_array($secondLevel, ['kardex', 'inventory', 'valued-kardex'])))?'show':'' }}" id="sidebarInventory">
                                <ul class="nav nav-sm flex-column">
                                    @if(in_array('inventory', $vc_module_levels))
                                        <li class="nav-item">
                                            <a class="nav-link {{ ($firstLevel === 'inventory')?'active':'' }}"
                                            href="{{route('inventory.index')}}">Movimientos</a>
                                        </li>
                                    @endif
                                    @if(in_array('inventory_transfers', $vc_module_levels))
                                        <li class="nav-item">
                                            <a class="nav-link {{ ($firstLevel === 'transfers')?'active':'' }}"
                                            href="{{route('transfers.index')}}">Traslados</a>
                                        </li>
                                    @endif
                                    @if(in_array('inventory_devolutions', $vc_module_levels))
                                        <li class="nav-item">
                                            <a class="nav-link {{ ($firstLevel === 'devolutions')?'active':'' }}"
                                            href="{{route('devolutions.index')}}">Devolucion a proveedor</a>
                                        </li>
                                    @endif
                                    @if(in_array('inventory_report_kardex', $vc_module_levels))
                                        <li class="nav-item">
                                            <a class="nav-link {{(($firstLevel === 'reports') && ($secondLevel === 'kardex')) ? 'active' : ''}}"
                                            href="{{route('reports.kardex.index')}}">Reporte Kardex</a>
                                        </li>
                                    @endif
                                    @if(in_array('inventory_report', $vc_module_levels))
                                        <li class="nav-item">
                                            <a class="nav-link {{(($firstLevel === 'reports') && ($secondLevel == 'inventory')) ? 'active' : ''}}"
                                            href="{{route('reports.inventory.index')}}">Reporte Inventario</a>
                                        </li>
                                    @endif
                                    @if(in_array('inventory_report_valued_kardex', $vc_module_levels))
                                        {{-- <li class="nav-item">
                                            <a class="nav-link {{ ($firstLevel === 'warehouses')?'active':'' }}" href="{{route('warehouses.index')}}">Almacenes</a>
                                        </li> --}}
                                        <li class="nav-item">
                                            <a class="nav-link {{(($firstLevel === 'reports') && ($secondLevel === 'valued-kardex')) ? 'active' : ''}}"
                                            href="{{route('reports.valued_kardex.index')}}">Kardex valorizado</a>
                                        </li>
                                    @endif
                                    @if(in_array('production_app', $vc_modules) && $configuration->isShowExtraInfoToItem())
                                        <li class="nav-item">
                                            <a class="nav-link {{($firstLevel === 'extra_info_items') ? 'active' : ''}}"
                                            href="{{route('extra_info_items.index')}}">Datos extra de items</a>
                                        </li>
                                    @endif
                                    @if($inventory_configuration->inventory_review)
                                        <li class="nac-item">
                                            <a class="nav-link {{ ($firstLevel === 'inventory-review')?'active':'' }}"
                                            href="{{route('tenant.inventory-review.index')}}">Revisión de
                                                inventario</a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </li>
                    @endif
                @endif

                {{-- establecimientos --}}
                @if(in_array('establishments', $vc_modules))
                    <li class="nav-item">
                        <a
                            class="nav-link menu-link {{ in_array($firstLevel, ['users', 'establishments'])?'active':'' }}"
                            href="#sidebarEstablishment"
                            data-bs-toggle="collapse"
                            role="button"
                            aria-expanded="{{ in_array($firstLevel, ['users', 'establishments'])?'true':'false' }}"
                            aria-controls="sidebarEstablishment">
                            <i class="ri-team-line"></i> <span>Usuarios/Locales & Series</span>
                        </a>
                        <div class="collapse menu-dropdown {{ in_array($firstLevel, ['users', 'establishments'])?'show':'' }}" id="sidebarEstablishment">
                            <ul class="nav nav-sm flex-column"
                                style="">
                                @if(in_array('users', $vc_module_levels))
                                    <li class="nav-item">
                                        <a class="nav-link {{ ($firstLevel === 'users')?'active':'' }}"
                                            href="{{route('tenant.users.index')}}">Usuarios</a>
                                    </li>
                                @endif
                                @if(in_array('users_establishments', $vc_module_levels))
                                    <li class="nav-item">
                                        <a class="nav-link {{ ($firstLevel === 'establishments')?'active':'' }}"
                                            href="{{route('tenant.establishments.index')}}">Establecimientos</a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </li>
                @endif

                {{-- avanzados --}}
                @if(in_array('advanced', $vc_modules) && $vc_company->soap_type_id != '03')
                    <li class="nav-item">
                        <a
                            class="nav-link menu-link {{ in_array($firstLevel, ['retentions','perceptions','order-forms','purchase-settlements','dispatches','drivers','dispatchers','transports','dispatch_carrier','origin_addresses', 'dispatch_addresses'])?'active':'' }}"
                            href="#sidebarAdvanced"
                            data-bs-toggle="collapse"
                            role="button"
                            aria-expanded="{{ in_array($firstLevel, ['retentions','perceptions','order-forms','purchase-settlements','dispatches','drivers','dispatchers','transports','dispatch_carrier','origin_addresses', 'dispatch_addresses'])?'true':'false' }}"
                            aria-controls="sidebarAdvanced">
                            <i class="ri-contacts-book-upload-line"></i> <span>Comprobantes avanzados</span>
                        </a>
                        <div class="collapse menu-dropdown {{ in_array($firstLevel, ['retentions','perceptions','order-forms','purchase-settlements','dispatches','drivers','dispatchers','transports','dispatch_carrier','origin_addresses', 'dispatch_addresses'])?'show':'' }}" id="sidebarAdvanced">
                            <ul class="nav nav-sm flex-column">
                                @if(in_array('advanced_retentions', $vc_module_levels))
                                    <li class="nav-item">
                                        <a class="nav-link {{ ($firstLevel === 'retentions')?'active':'' }}"
                                           href="{{route('tenant.retentions.index')}}">Retenciones</a>
                                    </li>
                                @endif
                                @if(in_array('advanced_perceptions', $vc_module_levels))
                                    <li class="nav-item">
                                        <a class="nav-link {{ ($firstLevel === 'perceptions')?'active':'' }}"
                                           href="{{route('tenant.perceptions.index')}}">Percepciones</a>
                                    </li>
                                @endif
                                @if(in_array('advanced_purchase_settlements', $vc_module_levels))
                                    <li class="nav-item">
                                        <a class="nav-link {{ ($firstLevel === 'purchase-settlements')?'active':'' }}"
                                           href="{{route('tenant.purchase-settlements.index')}}">Liquidaciones de
                                            compra</a>
                                    </li>
                                @endif
                                @if(in_array('advanced_order_forms', $vc_module_levels))
                                    <li class="nav-item">
                                        <a class="nav-link {{ ($firstLevel === 'order-forms')?'active':'' }}"
                                           href="{{route('tenant.order_forms.index')}}">Ordenes de pedido</a>
                                    </li>
                                @endif
                                <li class="nav-item">
                                    <a
                                        href="#sidebarDispatches"
                                        class="nav-link {{ in_array($firstLevel, ['dispatches','drivers','dispatchers','transports','dispatch_carrier','origin_addresses', 'dispatch_addresses'] )?'active' : '' }}"
                                        data-bs-toggle="collapse"
                                        role="button"
                                        aria-expanded="{{ in_array($firstLevel, ['dispatches','drivers','dispatchers','transports','dispatch_carrier','origin_addresses', 'dispatch_addresses'])?'true' : 'false' }}"
                                        aria-controls="sidebarDispatches">
                                        Guías de remisión
                                    </a>
                                    <div class="collapse menu-dropdown {{ in_array($firstLevel, ['dispatches','drivers','dispatchers','transports','dispatch_carrier','origin_addresses', 'dispatch_addresses'])?'show' : '' }}" id="sidebarDispatches">
                                        <ul class="nav nav-sm flex-column">
                                            <li class="nav-item">
                                                <a class="nav-link {{ ($firstLevel === 'dispatches')?'active':'' }}"
                                                href="{{route('tenant.dispatches.index')}}">G.R. Remitente</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link {{ ($firstLevel === 'dispatch_carrier')?'active':'' }}"
                                                href="{{route('tenant.dispatch_carrier.index')}}">G.R. Transportista</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link {{ ($firstLevel === 'dispatchers')?'active':'' }}"
                                                href="{{route('tenant.dispatchers.index')}}">Transportistas</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link {{ ($firstLevel === 'drivers')?'active':'' }}"
                                                href="{{route('tenant.drivers.index')}}">Conductores</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link {{ ($firstLevel === 'transports')?'active':'' }}"
                                                href="{{route('tenant.transports.index')}}">Vehículos</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link {{ ($firstLevel === 'origin_addresses')?'active':'' }}"
                                                href="{{route('tenant.origin_addresses.index')}}">Direcciones de
                                                    partida</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link {{ ($firstLevel === 'dispatch_addresses')?'active':'' }}"
                                                href="{{route('tenant.dispatch-addresses.index')}}">Direcciones de llegada</a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif

                {{-- reportes --}}
                @if(in_array('reports', $vc_modules))
                    <li class="nav-item">
                        <a class="nav-link menu-link {{  ($firstLevel === 'reports' && in_array($secondLevel, ['purchases', 'search','sales','customers','items', 'general-items','consistency-documents', 'quotations', 'sale-notes','cash','commissions','document-hotels', 'validate-documents', 'document-detractions','commercial-analysis', 'order-notes-consolidated', 'order-notes-general', 'sales-consolidated', 'user-commissions', 'fixed-asset-purchases', 'massive-downloads', 'tips', 'download-tray'])) ? 'active' : ''}} {{ in_array($firstLevel, ['list-reports', 'system-activity-logs']) ? 'active' : '' }}" href="{{ url('list-reports') }}">
                            <i class="ri-folders-fill"></i> <span>Reportes</span>
                        </a>
                    </li>
                @endif

                {{-- contabilidad --}}
                @if(in_array('accounting', $vc_modules))
                    <li class="nav-item {{ ($firstLevel === 'account' || $firstLevel === 'accounting_ledger'  )?'nav-active nav-expanded':'' }}">
                        <a
                            class="nav-link menu-link {{ in_array($firstLevel, ['account','accounting_ledger'])?'active':'' }}"
                            href="#sidebarAccounting"
                            data-bs-toggle="collapse"
                            role="button"
                            aria-expanded="{{ in_array($firstLevel, ['account','accounting_ledger'])?'true':'false' }}"
                            aria-controls="sidebarAccounting">
                            <i class="ri-calculator-line"></i> <span>Contabilidad</span>
                        </a>
                        <div class="collapse menu-dropdown {{ in_array($firstLevel, ['account','accounting_ledger'])?'show':'' }}" id="sidebarAccounting">
                            <ul class="nav nav-sm flex-column">
                                @if(in_array('account_report', $vc_module_levels))
                                    <li class="nav-item">
                                        <a class="nav-link {{(($firstLevel === 'account') && ($secondLevel === 'format')) ? 'active' : ''}}"
                                           href="{{ route('tenant.account_format.index') }}">Exportar reporte</a>
                                    </li>
                                @endif
                                @if(in_array('account_formats', $vc_module_levels))
                                    <li class="nav-item">
                                        <a class="nav-link {{(($firstLevel === 'account') && ($secondLevel == '')) ? 'active' : ''}}"
                                           href="{{ route('tenant.account.index') }}">Exportar formatos - Sis.
                                            Contable</a>
                                    </li>
                                @endif
                                @if(in_array('account_summary', $vc_module_levels))
                                    <li class="nav-item">
                                        <a class="nav-link {{(($firstLevel === 'account') && ($secondLevel == 'summary-report')) ? 'active' : ''}}"
                                           href="{{ route('tenant.account_summary_report.index') }}">Reporte resumido -
                                            Ventas</a>
                                    </li>
                                @endif
                                <li class="nav-item">
                                    <a class="nav-link {{(($firstLevel === 'accounting_ledger') ) ? 'active' : ''}}"
                                       href="{{ route('tenant.accounting_ledger.create') }}">
                                        Libro Mayor
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif

                {{-- finanzas --}}
                @if(in_array('finance', $vc_modules))
                    <li class="nav-item">
                        <a
                            class="nav-link menu-link {{ $firstLevel === 'finances' && in_array($secondLevel, ['global-payments','balance','payment-method-types','unpaid','to-pay','income','transactions','movements']) ? 'active' : ''}}"
                            href="#sidebarFinances"
                            data-bs-toggle="collapse"
                            role="button"
                            aria-expanded="{{ $firstLevel === 'finances' && in_array($secondLevel, ['global-payments','balance','payment-method-types','unpaid','to-pay','income','transactions','movements'])?'true':'false' }}"
                            aria-controls="sidebarFinances">
                            <i class="ri-funds-line"></i> <span>Finanzas</span>
                        </a>
                        <div class="collapse menu-dropdown {{ $firstLevel === 'finances' && in_array($secondLevel, ['global-payments','balance','payment-method-types','unpaid','to-pay','income','transactions','movements'])?'show':'' }}" id="sidebarFinances">
                            <ul class="nav nav-sm flex-column">
                                @if(in_array('finances_movements', $vc_module_levels))
                                    <li class="nav-item">
                                        <a class="nav-link {{(($firstLevel === 'finances') && ($secondLevel == 'movements')) ? 'active' : ''}}"
                                            href="{{route('tenant.finances.movements.index')}}">Movimientos</a>
                                    </li>
                                @endif
                                @if(in_array('finances_movements', $vc_module_levels))
                                    <li class="nav-item">
                                        <a class="nav-link {{(($firstLevel === 'finances') && ($secondLevel == 'transactions')) ? 'active' : ''}}"
                                            href="{{route('tenant.finances.transactions.index')}}">Transacciones</a>
                                    </li>
                                @endif
                                @if(in_array('finances_incomes', $vc_module_levels))
                                    <li class="nav-item">
                                        <a class="nav-link {{(($firstLevel === 'finances') && ($secondLevel == 'income')) ? 'active' : ''}}"
                                            href="{{route('tenant.finances.income.index')}}">Ingresos</a>
                                    </li>
                                @endif
                                @if(in_array('finances_unpaid', $vc_module_levels))
                                    <li class="nav-item">
                                        <a class="nav-link {{(($firstLevel === 'finances') && ($secondLevel == 'unpaid')) ? 'active' : ''}}"
                                            href="{{route('tenant.finances.unpaid.index')}}">Cuentas por cobrar</a>
                                    </li>
                                @endif
                                @if(in_array('finances_to_pay', $vc_module_levels))
                                    <li class="nav-item">
                                        <a class="nav-link {{(($firstLevel === 'finances') && ($secondLevel == 'to-pay')) ? 'active' : ''}}"
                                            href="{{route('tenant.finances.to_pay.index')}}">Cuentas por pagar</a>
                                    </li>
                                @endif
                                @if(in_array('finances_payments', $vc_module_levels))
                                    <li class="nav-item">
                                        <a class="nav-link {{(($firstLevel === 'finances') && ($secondLevel == 'global-payments')) ? 'active' : ''}}"
                                            href="{{route('tenant.finances.global_payments.index')}}">Pagos</a>
                                    </li>
                                @endif
                                @if(in_array('finances_balance', $vc_module_levels))
                                    <li class="nav-item">
                                        <a class="nav-link {{(($firstLevel === 'finances') && ($secondLevel == 'balance')) ? 'active' : ''}}"
                                            href="{{route('tenant.finances.balance.index')}}">Balance</a>
                                    </li>
                                @endif
                                @if(in_array('finances_payment_method_types', $vc_module_levels))
                                    <li class="nav-item">
                                        <a class="nav-link {{(($firstLevel === 'finances') && ($secondLevel == 'payment-method-types')) ? 'active' : ''}}"
                                            href="{{route('tenant.finances.payment_method_types.index')}}">Ingresos y
                                            Egresos - M. Pago</a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </li>
                @endif

                {{-- config --}}
                @if(in_array('configuration', $vc_modules))
                    <li class="nav-item">
                        <a class="nav-link menu-link {{in_array($firstLevel, ['list-platforms', 'list-cards', 'list-currencies', 'list-bank-accounts', 'list-banks', 'list-attributes', 'list-detractions', 'list-units', 'list-payment-methods', 'list-incomes', 'list-payments', 'company_accounts', 'list-vouchers-type',     'companies', 'advanced', 'tasks', 'inventories','bussiness_turns','offline-configurations','series-configurations','configurations', 'login-page', 'list-settings']) ? 'active' : ''}}" href="{{ url('list-settings') }}">
                            <i class="ri-settings-4-line"></i> <span>Configuración</span>
                        </a>
                    </li>
                @endif

                {{-- hotels --}}
                @if(in_array('hotels', $vc_modules))
                    <li class="nav-item {{ ($firstLevel === 'hotels') ? 'nav-active nav-expanded' : '' }}">
                        <a
                            class="nav-link menu-link {{ $firstLevel === 'hotels'  ? 'active' : ''}}"
                            href="#sidebarHotels"
                            data-bs-toggle="collapse"
                            role="button"
                            aria-expanded="{{ $firstLevel === 'hotels' ?'true':'false' }}"
                            aria-controls="sidebarHotels">
                            <i class="ri-hotel-fill"></i> <span>Hoteles</span>
                        </a>
                        <div class="collapse menu-dropdown {{ $firstLevel === 'hotels' ?'show':'' }}" id="sidebarHotels">
                            <ul class="nav nav-sm flex-column">
                                @if(in_array('hotels_reception', $vc_module_levels))
                                    <li class="nav-item">
                                        <a class="nav-link {{ (($firstLevel === 'hotels') && ($secondLevel === 'reception')) ? 'active' : '' }}"
                                            href="{{ url('hotels/reception') }}">Recepción</a>
                                    </li>
                                @endif
                                @if(in_array('hotels_rates', $vc_module_levels))
                                    <li class="nav-item">
                                        <a class="nav-link {{ (($firstLevel === 'hotels') && ($secondLevel === 'rates')) ? 'active' : '' }}"
                                            href="{{ url('hotels/rates') }}">Tarifas</a>
                                    </li>
                                @endif
                                @if(in_array('hotels_floors', $vc_module_levels))
                                    <li class="nav-item">
                                        <a class="nav-link {{ (($firstLevel === 'hotels') && ($secondLevel === 'floors')) ? 'active' : '' }}"
                                            href="{{ url('hotels/floors') }}">Pisos</a>
                                    </li>
                                @endif
                                @if(in_array('hotels_cats', $vc_module_levels))
                                    <li class="nav-item">
                                        <a class="nav-link {{ (($firstLevel === 'hotels') && ($secondLevel === 'categories')) ? 'active' : '' }}"
                                            href="{{ url('hotels/categories') }}">Categorías</a>
                                    </li>
                                @endif
                                @if(in_array('hotels_rooms', $vc_module_levels))
                                    <li class="nav-item">
                                        <a class="nav-link {{ (($firstLevel === 'hotels') && ($secondLevel === 'rooms')) ? 'active' : '' }}"
                                            href="{{ url('hotels/rooms') }}">Habitaciones</a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </li>
                @endif

                {{-- documentary --}}
                @if(in_array('documentary-procedure', $vc_modules))
                    <li class="nav-item">
                        <a
                            class="nav-link menu-link {{ ($firstLevel === 'documentary-procedure') ? 'active' : '' }}"
                            href="#sidebarDocumentary"
                            data-bs-toggle="collapse"
                            role="button"
                            aria-expanded="{{ ($firstLevel === 'documentary-procedure') ?'true':'false' }}"
                            aria-controls="sidebarDocumentary">
                            <i class="ri-file-edit-line"></i> <span>Trámite documentario</span>
                        </a>
                        <div class="collapse menu-dropdown {{ ($firstLevel === 'documentary-procedure') ?'show':'' }}" id="sidebarDocumentary">
                            <ul class="nav nav-sm flex-column">
                                @if(in_array('documentary_offices', $vc_module_levels))
                                    <li class="nav-item">
                                        <a class="nav-link {{(($firstLevel==='documentary-procedure')&&($secondLevel==='offices')) ? 'active' : '' }}"
                                           href="{{ route('documentary.offices') }}">Listado de Etapas</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{(($firstLevel==='documentary-procedure')&&($secondLevel==='status')) ? 'active' : '' }}"
                                           href="{{ route('documentary.status') }}">Listado de Estados</a>
                                    </li>
                                @endif
                                @if(in_array('documentary_process', $vc_module_levels))
                                    <li class="nav-item">
                                        <a class="nav-link {{(($firstLevel==='documentary-procedure')&&($secondLevel==='requirements')) ? 'active' : '' }}"
                                           href="{{ route('documentary.requirements') }}">Listado de requisitos</a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link {{(($firstLevel==='documentary-procedure')&&($secondLevel==='processes')) ? 'active' : '' }}"
                                           href="{{ route('documentary.processes') }}">Tipos de Trámites</a>
                                    </li>
                                @endif
                                @if(in_array('documentary_files', $vc_module_levels))
                                    <li class="nav-item">
                                        <a class="nav-link {{($firstLevel==='documentary-procedure' && in_array($secondLevel,['files_simplify','files']))?'active': ''}}"
                                           href="{{ route('documentary.files_simplify') }}">Listado de Trámites</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{(($firstLevel==='documentary-procedure')&&(($secondLevel==='stadistic'))) ? 'active' : '' }}"
                                           href="{{ route('documentary.stadistic') }}">Estadisticas de Trámites</a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </li>
                @endif

                {{-- DIGEMID --}}
                @if(in_array('digemid', $vc_modules) && $configuration->isPharmacy())
                    <li class="nav-item">
                        <a
                            class="nav-link menu-link {{ ($firstLevel === 'digemid') ? 'active' : '' }}"
                            href="#sidebarDigemid"
                            data-bs-toggle="collapse"
                            role="button"
                            aria-expanded="{{ ($firstLevel === 'digemid') ?'true':'false' }}"
                            aria-controls="sidebarDigemid">
                            <i class="ri-hospital-line"></i> <span>Farmacia</span>
                        </a>
                        <div class="collapse menu-dropdown {{ ($firstLevel === 'digemid') ?'show':'' }}" id="sidebarDigemid">
                            <ul class="nav nav-sm flex-column">
                                @if(in_array('digemid', $vc_module_levels))
                                    <li class="nav-item">
                                        <a class="nav-link {{ $firstLevel === 'digemid' ? 'active' : '' }}"
                                        href="{{ route('tenant.digemid.index') }}">Productos</a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </li>
                @endif

                {{-- Suscription --}}
                @if(in_array('full_subscription_app', $vc_modules) )
                    <li class="nav-item">
                        <a
                            class="nav-link menu-link {{ ($firstLevel === 'full_subscription') ? 'active' : '' }}"
                            href="#sidebarSuscription"
                            data-bs-toggle="collapse"
                            role="button"
                            aria-expanded="{{ ($firstLevel === 'full_subscription') ?'true':'false' }}"
                            aria-controls="sidebarSuscription">
                            <i class="ri-contacts-fill"></i> <span>Suscripción Servicios SAAS</span>
                        </a>
                        <div class="collapse menu-dropdown {{ ($firstLevel === 'full_subscription') ?'show':'' }}" id="sidebarSuscription">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a class="nav-link {{ ($firstLevel === 'full_subscription' && $secondLevel === 'client')?'active':'' }}"
                                    href="{{ route('tenant.fullsubscription.client.index') }}">
                                        Clientes
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ (($firstLevel === 'full_subscription') && ($secondLevel === 'plans')) ? 'active' : '' }}"
                                    href="{{ route('tenant.fullsubscription.plans.index') }}">
                                        Planes
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ (($firstLevel === 'full_subscription') && ($secondLevel === 'payments')) ? 'active' : '' }}"
                                    href="{{ route('tenant.fullsubscription.payments.index') }}">
                                        Suscripciones
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ (($firstLevel === 'full_subscription') && ($secondLevel === 'payment_receipt')) ? 'active' : '' }}"
                                    href="{{ route('tenant.fullsubscription.payment_receipt.index') }}">
                                        Recibos de pago
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif

                {{-- subscription Escolar--}}
                @if(in_array('subscription_app', $vc_modules) )
                    <li class="nav-item">
                        <a
                            class="nav-link menu-link {{ ($firstLevel === 'subscription') ? 'active' : '' }}"
                            href="#sidebarSchool"
                            data-bs-toggle="collapse"
                            role="button"
                            aria-expanded="{{ ($firstLevel === 'subscription') ?'true':'false' }}"
                            aria-controls="sidebarSchool">
                            <i class="ri-group-2-fill"></i> <span>Suscripción Escolar</span>
                        </a>
                        <div class="collapse menu-dropdown {{ ($firstLevel === 'subscription') ?'show':'' }}" id="sidebarSchool">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a
                                        href="#sidebarFixedAssets"
                                        class="nav-link {{ ($firstLevel === 'subscription' && $secondLevel === 'client') ? 'active' : '' }}"
                                        data-bs-toggle="collapse"
                                        role="button"
                                        aria-expanded="{{ ($firstLevel === 'subscription' && $secondLevel === 'client')?'true' : 'false' }}"
                                        aria-controls="sidebarFixedAssets">
                                        Clientes
                                    </a>
                                    <div class="collapse menu-dropdown {{ ($firstLevel === 'subscription' && $secondLevel === 'client')?'show' : '' }}" id="sidebarFixedAssets">
                                        <ul class="nav nav-sm flex-column">
                                            <li class="nav-item">
                                                <a class="nav-link {{ ( ($firstLevel === 'subscription') && ($secondLevel === 'client')  && ($thridLevel !== 'childrens') )?'active':'' }}"
                                                href="{{ route('tenant.subscription.client.index') }}">
                                                    Padres
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link {{ ( ($firstLevel === 'subscription') && ($secondLevel === 'client') && ($thridLevel === 'childrens') )?'active':'' }}"
                                                href="{{ route('tenant.subscription.client_children.index') }}">
                                                    Hijos
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ (($firstLevel === 'subscription') && ($secondLevel === 'plans')) ? 'active' : '' }}"
                                    href="{{ route('tenant.subscription.plans.index') }}">
                                        Planes
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ (($firstLevel === 'subscription') && ($secondLevel === 'payments')) ? 'active' : '' }}"
                                    href="{{ route('tenant.subscription.payments.index') }}">
                                        Matrículas
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ (($firstLevel === 'subscription') && ($secondLevel === 'payment_receipt')) ? 'active' : '' }}"
                                    href="{{ route('tenant.subscription.payment_receipt.index') }}">
                                        Recibos de pago
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ (($firstLevel === 'subscription') && ($secondLevel === 'grade_section')) ? 'active' : '' }}"
                                    href="{{ route('tenant.subscription.grade_section.index') }}">
                                        Grados y Secciones
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif

                {{-- Produccion --}}
                @if(in_array('production_app', $vc_modules) )
                    <li class="nav-item">
                        <a
                            class="nav-link menu-link {{ in_array($firstLevel, ['production','machine-production','packaging','machine-type-production','workers','mill-production']) ? 'active' : '' }}"
                            href="#sidebarProduction"
                            data-bs-toggle="collapse"
                            role="button"
                            aria-expanded="{{ in_array($firstLevel, ['production','machine-production','packaging','machine-type-production','workers','mill-production']) ?'true':'false' }}"
                            aria-controls="sidebarProduction">
                            <i class="ri-building-2-line"></i> <span>Producción</span>
                        </a>
                        <div class="collapse menu-dropdown {{ in_array($firstLevel, ['production','machine-production','packaging','machine-type-production','workers','mill-production']) ?'show':'' }}" id="sidebarProduction">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a class="nav-link {{ (($firstLevel === 'production') ) ? 'active' : '' }}"
                                    href="{{ route('tenant.production.index') }}">
                                        Productos Fabricados
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ (($firstLevel === 'mill-production')) ? 'active' : '' }}"
                                    href="{{ route('tenant.mill_production.index') }}">
                                        Ingreso de Insumos
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ (($firstLevel === 'machine-type-production')) ? 'active' : '' }}"
                                    href="{{ route('tenant.machine_type_production.index') }}">
                                        Tipos de maquinaria
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ (($firstLevel === 'machine-production')) ? 'active' : '' }}"
                                    href="{{ route('tenant.machine_production.index') }}">
                                        Maquinaria
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ (($firstLevel === 'packaging')) ? 'active' : '' }}"
                                    href="{{ route('tenant.packaging.index') }}">
                                        Zona de embalaje
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ (($firstLevel === 'workers')) ? 'active' : '' }}"
                                    href="{{ route('tenant.workers.index') }}">
                                        Empleados
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif

                {{-- Restaurante --}}
                @if(in_array('restaurant_app', $vc_modules))
                    <li class="nav-item">
                        <a
                            class="nav-link menu-link {{ ($firstLevel === 'restaurant') ? 'active' : '' }}"
                            href="#sidebarRestaurant"
                            data-bs-toggle="collapse"
                            role="button"
                            aria-expanded="{{ ($firstLevel === 'restaurant') ?'true':'false' }}"
                            aria-controls="sidebarRestaurant">
                            <i class="ri-restaurant-2-line"></i> <span>Restaurante</span>
                        </a>
                        <div class="collapse menu-dropdown {{ ($firstLevel === 'restaurant') ?'show':'' }}" id="sidebarRestaurant">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a
                                        class="nav-link {{ ($secondLevel != null && $secondLevel == 'cash' && $thridLevel == 'pos')?'active':'' }}"
                                        href="#sidebarRestaurantPos"
                                        data-bs-toggle="collapse"
                                        role="button"
                                        aria-expanded="{{ ($secondLevel != null && $secondLevel == 'cash' && $thridLevel == 'pos') ?'true':'false' }}"
                                        aria-controls="sidebarRestaurantPos">
                                        POS
                                    </a>

                                    <div class="collapse menu-dropdown {{ ($secondLevel != null && $secondLevel == 'cash' && $thridLevel == 'pos') ?'show':'' }}" id="sidebarRestaurantPos">
                                        <ul class="nav nav-sm flex-column">
                                            <li class="nav-item">
                                                <a class="nav-link {{ ($secondLevel != null && $secondLevel == 'cash' && $thridLevel == 'pos')?'active':'' }}"
                                                href="{{route('tenant.restaurant.cash.filter-pos')}}">
                                                    Caja Chica
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                <li class="nav-item">
                                    <a
                                        class="nav-link {{ ($secondLevel != null && $secondLevel == 'cash' && $thridLevel == '')?'active':'' }}"
                                        href="#sidebarRestaurantMesas"
                                        data-bs-toggle="collapse"
                                        role="button"
                                        aria-expanded="{{ ($secondLevel != null && $secondLevel == 'cash' && $thridLevel == '') ?'true':'false' }}"
                                        aria-controls="sidebarRestaurantMesas">
                                        Mesas
                                    </a>
                                    <div class="collapse menu-dropdown {{ ($secondLevel != null && $secondLevel == 'cash' && $thridLevel == '') ?'show':'' }}" id="sidebarRestaurantMesas">
                                        <ul class="nav nav-sm flex-column">
                                            <li class="nav-item">
                                                <a class="nav-link {{ ($secondLevel != null && $secondLevel == 'cash' && $thridLevel == '')?'active':'' }}"
                                                href="{{route('tenant.restaurant.cash.index')}}">
                                                    Caja Chica
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                <li class="nav-item">
                                    <a
                                        class="nav-link {{ ( $secondLevel != null && $secondLevel == 'promotions') || ( $secondLevel != null && $secondLevel == 'orders') ?'active':'' }}"
                                        href="#sidebarRestaurantPedidos"
                                        data-bs-toggle="collapse"
                                        role="button"
                                        aria-expanded="{{ ( $secondLevel != null && $secondLevel == 'promotions') || ( $secondLevel != null && $secondLevel == 'orders') ?'true':'false' }}"
                                        aria-controls="sidebarRestaurantPedidos">
                                        Pedidos
                                    </a>
                                    <div class="collapse menu-dropdown {{ ( $secondLevel != null && $secondLevel == 'promotions') || ( $secondLevel != null && $secondLevel == 'orders') ?'show':'' }}" id="sidebarRestaurantPedidos">
                                        <ul class="nav nav-sm flex-column">
                                            <li class="nav-item">
                                                <a class="nav-link"
                                                href="{{ route('tenant.restaurant.menu') }}"
                                                target="blank">
                                                    Ver Menu
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link {{ ( $secondLevel != null && $secondLevel == 'promotions')?'active':'' }}"
                                                href="{{route('tenant.restaurant.promotion.index')}}">
                                                    Promociones(Banners)
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link {{ ( $secondLevel != null && $secondLevel == 'orders')?'active':'' }}"
                                                href="{{route('tenant.restaurant.order.index')}}">
                                                    Pedidos
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ ( $secondLevel != null && $secondLevel == 'list' && $firstLevel === 'restaurant' ) ? 'active' : '' }}"
                                    href="{{ route('tenant.restaurant.list_items') }}">
                                        Productos
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ ( $secondLevel != null && $secondLevel == 'configuration' && $firstLevel === 'restaurant' ) ? 'active' : '' }}"
                                    href="{{ route('tenant.restaurant.configuration') }}">
                                        Configuración
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif

                @if(in_array('generate_link_app', $vc_modules))
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ ($firstLevel === 'payment-links')?'active':'' }}" href="{{ route('tenant.payment.generate.index') }}">
                            <i class="ri-links-line"></i> <span>Generador de link de pago</span>
                        </a>
                    </li>
                @endif

                @if(in_array('app_2_generator', $vc_modules))
                    <li class="nav-item">
                        <a class="nav-link menu-link  {{ ($firstLevel === 'live-app')?'active':'' }}"
                            href="{{ route('tenant.liveapp.configuration') }}">
                            <i class="ri-ruler-2-line"></i>
                            <span>Generador APP 2.0</span>
                        </a>
                    </li>
                @endif

                {{-- APP --}}
                @if(in_array('apps', $vc_modules))
                    <li class="nav-item">
                        <a class="nav-link menu-link  {{ ($firstLevel === 'list-extras')?'active':'' }}"
                        href="{{url('list-extras')}}">
                            <i class="bx bx-category-alt"></i>
                            <span>Apps</span>
                        </a>
                    </li>
                @endif

                @foreach (Module::all() as $module)
                    @includeIf('tenant.layouts.partials.addons_menu.'.$module->getLowerName())
                @endforeach

            </ul>
        </div>
        <!-- Sidebar -->
    </div>
    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->
<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>
