<div class="accordion" id="order-entries-accordion" role="tablist" aria-multiselectable="true">
    @foreach($configuration as $configEntry)
        <div class="panel">
            <a class="panel-heading {{ !$loop->first ? 'collapsed' : '' }}"
               role="tab" id="heading-{{ $loop->iteration }}"
               data-toggle="collapse" data-parent="#accordion"
               href="#collapse-{{ $loop->iteration }}" aria-expanded="false"
               aria-controls="collapse-{{ $loop->iteration }}">
                <h4 class="panel-title">
                    {{ $loop->iteration }}. {{ $configEntry['framework'] . ' / ' . $configEntry['name'] }}
                </h4>
            </a>
            <div id="collapse-{{ $loop->iteration }}" class="panel-collapse collapse" role="tabpanel"
                 aria-labelledby="heading-{{ $loop->iteration }}">
                <div class="panel-body">
                    <p><b>Framework:</b> {{ $configEntry['framework'] }}</p>
                    <p><b>Algorithm:</b> {{ $configEntry['name'] }}</p>
                    <p><b>Parameters:</b></p>
                    @if(count($configEntry['params']))
                        <ul>
                            @foreach($configEntry['params'] as $name => $value)
                                <li><b>{{ $name }}</b>: {{ $value }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    @endforeach
</div>
