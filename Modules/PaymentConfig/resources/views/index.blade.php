<x-app-layout :assets="$assets ?? []" title='Settings' :isBanner="true">

<div>
    <div class="row">
        <div class="col-sm-12 col-lg-12">
            <div class="card">
                 @foreach ($paymentModules as $module)
                    <div class="panel panel-default">
                        <div class="panel-body">
                            {{-- Render settings form or display info --}}
                            @if (view()->exists(strtolower($module['name']) . '::config'))
                                @include(strtolower($module['name']) . '::config', ['config' => $paymentConfigs[strtolower(str_replace('Payment', '', $module['name']))]->config ?? []])
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

</x-app-layout>
