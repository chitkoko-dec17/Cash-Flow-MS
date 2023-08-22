<div class="container-fluid">
    <div class="page-header">
      <div class="row">
        <div class="col-lg-6">
          {{ $breadcrumb_title ?? '' }}
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('index') }}">Dashboard</a></li>
              {{ $slot ?? ''}}
          </ol>
        </div>
        <div class="col-lg-6">
        </div>
      </div>
    </div>
</div>
