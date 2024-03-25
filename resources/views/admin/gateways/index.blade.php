@include('layouts.head')
@include('partials.header')

    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route("admin.gateways.create") }}">
                {{ trans('global.add') }} {{ trans('cruds.gateway.title_singular') }}
            </a>
        </div>
    </div>

<div class="card">
    <div class="card-header">
        {{ trans('cruds.gateway.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-gateway">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.gateway.fields.id') }}
                        </th>
                        <th>
                            Sort
                        </th>
                        <th>
                            {{ trans('cruds.gateway.fields.ec_pay_gateway_name') }}
                        </th>
                        <th>
                            {{ trans('cruds.gateway.fields.ec_pay_gateway_url') }}
                        </th>
                        <th>
                            {{ trans('cruds.gateway.fields.ec_pay_gateway_currency') }}
                        </th>
                        <th>
                            {{ trans('cruds.gateway.fields.ec_pay_gateway_image') }}
                        </th>
                        <th>
                            {{ trans('cruds.gateway.fields.ec_pay_gateway_enabled') }}
                        </th>
                        <th>
                            Description
                        </th>
                        <th>
                            {{ trans('cruds.gateway.fields.created_at') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($gateways as $key => $gateway)
                        <tr data-entry-id="{{ $gateway->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $gateway->id ?? '' }}
                            </td>
                            <td>
                                {{ $gateway->ec_pay_gateway_sort ?? '' }}
                            </td>
                            <td>
                                {{ $gateway->ec_pay_gateway_name ?? '' }}
                            </td>
                            <td>
                                {{ $gateway->ec_pay_gateway_url ?? '' }}
                            </td>
                            <td>
                                {{ $gateway->ec_pay_gateway_currency ?? '' }}
                            </td>
                            <td>
                                {{ $gateway->ec_pay_gateway_image ?? '' }}
                            </td>
                            <td>
                                {{ $gateway->ec_pay_gateway_enabled ?? '' }}
                            </td>
                            <td>
                                {{ $gateway->ec_pay_gateway_description ?? '' }}
                            </td>
                            <td>
                                {{ $gateway->created_at ?? '' }}
                            </td>
                            <td>
                                
                                <a class="btn btn-xs btn-primary" href="{{ route('admin.gateways.show', $gateway->id) }}">
                                    {{ trans('global.view') }}
                                </a>
                            
                                <a class="btn btn-xs btn-info" href="{{ route('admin.gateways.edit', $gateway->id) }}">
                                    {{ trans('global.edit') }}
                                </a>
                            
                                <form action="{{ route('admin.gateways.destroy', $gateway->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                </form>
                                

                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>


    </div>
</div>

<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)

  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.gateways.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)


  $.extend(true, $.fn.dataTable.defaults, {
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  });
  $('.datatable-gateway:not(.ajaxTable)').DataTable({ buttons: dtButtons })
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });
})

</script>
@include('layouts.footer')