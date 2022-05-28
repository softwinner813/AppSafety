{{-- Header --}}
<div id="kt_header" class="header header-fixed">

    <div class="container d-flex align-items-stretch justify-content-between">
        <!--begin::Left-->
        <div class="d-flex align-items-stretch mr-3">
            <!--begin::Header Logo-->
            <div class="header-logo">
                <a href="index.html">
                    <img alt="Logo" src="/media/logos/logo-letter-9.png" class="logo-default max-h-40px" />
                    <img alt="Logo" src="/media/logos/logo-letter-1.png" class="logo-sticky max-h-40px" />
                </a>
            </div>
            <!--end::Header Logo-->
            <!--begin::Header Menu Wrapper-->
            <div class="header-menu-wrapper header-menu-wrapper-left" id="kt_header_menu_wrapper">
                <!--begin::Header Menu-->
                <div id="kt_header_menu" class="header-menu header-menu-left header-menu-mobile header-menu-layout-default">
                    <!--begin::Header Nav-->
                    <ul class="menu-nav">
                        <li class="menu-item menu-item-open menu-item-here menu-item-submenu menu-item-rel menu-item-open menu-item-here" data-menu-toggle="click" aria-haspopup="true">
                            <a href="/home" class="menu-link ">
                                <span class="menu-text">Home</span>
                                <i class="menu-arrow"></i>
                            </a>
                        </li>
                        <li class="menu-item menu-item-submenu menu-item-rel" data-menu-toggle="click" aria-haspopup="true">
                            <a href="/policy" class="menu-link ">
                                <span class="menu-text">Policy</span>
                                <span class="menu-desc"></span>
                                <i class="menu-arrow"></i>
                            </a>
                        </li>
                        <li class="menu-item menu-item-submenu menu-item-rel" data-menu-toggle="click" aria-haspopup="true">
                            <a href="/ra" class="menu-link ">
                                <span class="menu-text">Risk Assessment</span>
                                <span class="menu-desc"></span>
                                <i class="menu-arrow"></i>
                            </a>
                        </li>
                        <li class="menu-item menu-item-submenu menu-item-rel" data-menu-toggle="click" aria-haspopup="true">
                            <a href="javascript:;" class="menu-link">
                                <span class="menu-text">AUDITS</span>
                                <span class="menu-desc"></span>
                                <i class="menu-arrow"></i>
                            </a>
                            
                        </li>
                        <li class="menu-item menu-item-submenu" data-menu-toggle="click" aria-haspopup="true">
                            <a href="/permit" class="menu-link ">
                                <span class="menu-text">Permits</span>
                                <span class="menu-desc"></span>
                                <i class="menu-arrow"></i>
                            </a>
                        </li>
                        <li class="menu-item menu-item-submenu" data-menu-toggle="click" aria-haspopup="true">
                            <a href="javascript:;" class="menu-link ">
                                <span class="menu-text">Guidances</span>
                                <span class="menu-desc"></span>
                                <i class="menu-arrow"></i>
                            </a>
                        </li>
                        
                        <li class="menu-item menu-item-submenu" data-menu-toggle="click" aria-haspopup="true">
                            <a href="javascript:;" class="menu-link ">
                                <span class="menu-text">Contact US</span>
                                <span class="menu-desc"></span>
                                <i class="menu-arrow"></i>
                            </a>
                        </li>
                    </ul>
                    <!--end::Header Nav-->
                </div>
                <!--end::Header Menu-->
            </div>
            <!--end::Header Menu Wrapper-->
        </div>
        <!--end::Left-->
        <!--begin::Topbar-->
        <div class="topbar">
            
            <!--begin::User-->
            @include('layout.partials.extras._topbar')
            
            <!--end::User-->
        </div>
        <!--end::Topbar-->
    </div>
</div>
