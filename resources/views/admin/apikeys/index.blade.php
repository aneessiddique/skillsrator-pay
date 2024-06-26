@include('layouts.head')
@include('partials.header')

    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route("admin.apikeys.create") }}">
                {{ trans('global.add') }} {{ trans('cruds.apikey.title_singular') }}
            </a>
        </div>
    </div>

<div class="card">
    <div class="card-header">
        {{ trans('cruds.apikey.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-apikey">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.apikey.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.apikey.fields.ec_pay_app_name') }}
                        </th>
                        <th>
                            {{ trans('cruds.apikey.fields.ec_pay_api_key') }}
                        </th>
                        <th>
                            {{ trans('cruds.apikey.fields.ec_pay_api_iv') }}
                        </th>
                        <th>
                            {{ trans('cruds.apikey.fields.ec_pay_api_token') }}
                        </th>
                        <th>
                            {{ trans('cruds.apikey.fields.created_at') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($apikeys as $key => $apikey)
                        <tr data-entry-id="{{ $apikey->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $apikey->id ?? '' }}
                            </td>
                            <td>
                                {{ $apikey->ec_pay_app_name ?? '' }}
                            </td>
                            <td>
                                {{ $apikey->ec_pay_api_key ?? '' }}
                            </td>
                            <td>
                                {{ $apikey->ec_pay_api_iv ?? '' }}
                            </td>
                            <td>
                                {{ $apikey->ec_pay_api_token ?? '' }}
                            </td>
                            <td>
                                {{ $apikey->created_at ?? '' }}
                            </td>
                            <td>
                                
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.apikeys.show', $apikey->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.apikeys.edit', $apikey->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                
                                    <form action="{{ route('admin.apikeys.destroy', $apikey->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
    url: "{{ route('admin.apikeys.massDestroy') }}",
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
  $('.datatable-apikey:not(.ajaxTable)').DataTable({ buttons: dtButtons })
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });
})

</script>
@include('layouts.footer')