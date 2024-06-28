<x-app-layout :assets="$assets ?? []" title='File Manager' :isBanner="true">
        <div class="row">
            <div class="col-sm-12 col-lg-12">
                <div class="card">
                     <iframe src="{{ route('unisharp.lfm.show') }}" style="width: 100%; height: 680px; border: none;"></iframe>
                </div>
            </div>
        </div>
</x-app-layout>
