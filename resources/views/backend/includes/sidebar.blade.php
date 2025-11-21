<div id="kt_aside" class="aside" data-kt-drawer="true" data-kt-drawer-name="aside" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_aside_mobile_toggle">
  <div class="aside_brand">
    <span>Chandra Asri</span>
    <h4>Anti CMS</h4>
  </div>

  <div class="circle-divider">.</div>
  @include('backend.includes.partials.menu')
</div>
<form id="logout-form" action="{{ route('frontend.auth.logout') }}" method="POST" class="d-none">
  @csrf
</form>