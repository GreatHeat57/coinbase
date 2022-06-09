<!DOCTYPE html>
<html>
<head>
    <title>Coinbase</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
</head>
<body>
    
<div class="container pt-5">
    <h1>Coinbase</h1>
    <a class="btn btn-success my-3" href="javascript:void(0)" id="createNewAsset"> Create New Asset</a>
    <table class="table table-bordered data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Asset ID</th>
                <th>Price</th>
                <th>Data Start</th>
                <th>Data End</th>
                <th width="280px">Action</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
   
<div class="modal fade" id="ajaxModel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
            </div>
            <div class="modal-body">
                <form id="assetForm" name="assetForm" class="form-horizontal">
                   <input type="hidden" name="asset_id" id="asset_id">
                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" value="" maxlength="50" required="">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="assetId" class="col-sm-2 control-label">Asset ID</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="assetId" name="assetId" placeholder="Enter Asset ID" value="" maxlength="50" required="">
                        </div>
                    </div>
     
                    <div class="form-group">
                        <label for="price" class="col-sm-2 control-label">Price</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="price" name="price" placeholder="Enter price" value="" maxlength="50" required="">
                        </div>
                    </div>
      
                    <div class="col-sm-offset-2 col-sm-10">
                     <button type="submit" class="btn btn-primary" id="saveBtn" value="create">Save changes
                     </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
    
</body>
    
<script type="text/javascript">
  $(function () {
     
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
    });
    
    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('assets.index') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'name', name: 'name'},
            {data: 'asset_id', name: 'asset_id'},
            {data: 'price_usd', name: 'price_usd'},
            {data: 'data_start', name: 'data_start'},
            {data: 'data_end', name: 'data_end'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });
     
    $('#createNewAsset').click(function () {
        $('#saveBtn').val("create-asset");
        $('#asset_id').val('');
        $('#assetForm').trigger("reset");
        $('#modelHeading').html("Create New Asset");
        $('#ajaxModel').modal('show');
    });
    
    $('body').on('click', '.editAsset', function () {
      var asset_id = $(this).data('id');
      $.get("{{ route('assets.index') }}" +'/' + asset_id +'/edit', function (data) {
          $('#modelHeading').html("Edit Asset");
          $('#saveBtn').val("edit-user");
          $('#ajaxModel').modal('show');
          $('#asset_id').val(data.id);
          $('#name').val(data.name);
          $('#price').val(data.price_usd);
          $('#assetId').val(data.asset_id);
      })
   });
    
    $('#saveBtn').click(function (e) {
        e.preventDefault();
        $(this).html('Sending..');
    
        $.ajax({
          data: $('#assetForm').serialize(),
          url: "{{ route('assets.store') }}",
          type: "POST",
          dataType: 'json',
          success: function (data) {
     
              $('#assetForm').trigger("reset");
              $('#ajaxModel').modal('hide');
              table.draw();
              $('#saveBtn').html('Save Changes');
          },
          error: function (data) {
              console.log('Error:', data);
              $('#saveBtn').html('Save Changes');
          }
      });
    });
    
    $('body').on('click', '.deleteAsset', function () {
     
        var asset_id = $(this).data("id");
        confirm("Are You sure want to delete !");
      
        $.ajax({
            type: "DELETE",
            url: "{{ route('assets.store') }}"+'/'+asset_id,
            success: function (data) {
                table.draw();
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    });
     
  });
</script>
</html>