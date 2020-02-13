@extends('adminlte::page')

@section('title', 'Verifier Management')

@section('content')
<div class="row">
<div class="col-md-12">

  <div class="panel panel-default">
            <div class="panel-heading">            
         <h3 class="panel-title pull-left">Set Default Verifier
            </h3>
        <div class="clearfix"></div>

            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
@if (session()->has('sysmsg_type'))
        <div class="alert alert-{{ session()->get('sysmsg_class') }} alert-dismissible">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          <strong><i class="{{ session()->get('sysmsg_icon') }} "></i> {{ session()->get('sysmsg_text') }}</strong>
        </div>
@endif
<!-- <div class="table-responsive"> -->
<table id="verifierList" class="table">
  <thead>
    <tr>
      <th>Group Code</th>
      <th>Verifier Name</th>
      <th>Verifier ID</th>
      <th>Staff Count</th>
      <th>Action</th>
    </tr>
    </thead>
    <tbody>

@foreach($verifierGroups as $row_verifierGroups)
    <tr>
      <td>{{ $row_verifierGroups->group_code }}</td>
      <td>{{ $row_verifierGroups->Verifier->name }}</td>
      <td>{{ $row_verifierGroups->Verifier->staff_no }}</td>
      <td>{{ $row_verifierGroups->Members()->count() }}</td>
      <td>
      <form method="post" action="{{ route('verifier.delGroup', [], false) }}" id="fd{{ $row_verifierGroups->id }}">
               @csrf
               <a href="{{ route('verifier.viewGroup', ['gid' => $row_verifierGroups->id], false) }}">
               <button type="button" class="btn btn-np" title="Edit">
               <i class="fas fa-pencil-alt"></i></button></a>
               <button type="button" class="btn btn-np" title="Delete" 
               onclick="return deletefile('{{ $row_verifierGroups->id }}','{{ $row_verifierGroups->group_name }}')"><i class="fas fa-trash-alt"></i></button>
               <input type="hidden" name="id" value="{{ $row_verifierGroups->id }}" />
             </form>
             
      </td>
    </tr>  
@endforeach   

  </tbody>
</table>
<!-- </div> -->
            </div><!-- /.panel-body -->
          </div><!-- /.panel panel-info -->
</div><!-- /.col-md-12 -->
</div><!-- /.row -->


<div class="row">
<div class="col-md-12">

  <div class="panel panel-default">
            <div class="panel-heading">            
         <h3 class="panel-title">Create Group Verifier</h3>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
<form class="form-horizontal" action="{{ route('verifier.createGroup',[],false) }}" method="post">
@csrf
  <div class="form-group">
    <label class="control-label col-sm-2" for="groupname">Group Name:</label>
    <div class="col-sm-4">
      <input name="groupname" type="text" class="form-control" id="groupname" placeholder="Enter group name" required
      maxlength="150">
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="groupcode">Group Code:</label>
    <div class="col-sm-4">
      <input name="groupcode" type="text" class="form-control" id="groupcode" placeholder="Enter group code" required
      maxlength="16">
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="verifierid">Verifier Name:</label>
    <div class="col-sm-4">
      <select id="selectVerifierId" class="verifierListId form-control" name="verifierId" required ></select>
      
  <div class="checkbox">
      <!-- Trigger the modal with a href -->
      <a href="#" data-toggle="modal" data-target="#modalAdvSearch" 
      data-backdrop="static" data-keyboard="false" class="btn btn-primary btn-xs">
      <i class="glyphicon glyphicon-search"></i> Advance Search</a>
  </div>

  </div>    
  </div>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-primary btn-outline">Create</button>
    </div>
  </div>
</form> 

            </div><!-- /.panel-body -->
          </div><!-- /.panel panel-info -->

</div><!-- /.col-md-12 -->
</div><!-- /.row -->        

<!-- Modal -->
<div id="modalAdvSearch" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Advance Search</h4>
      </div>
      <div id="modal-body" class="modal-body">
      <div id="advSearchAlertMsg"></div>

<div id="resultAdvSearch" class="table-responsive"></div>      
<fieldset>
<legend>Search Creteria</legend>
<form class="form-horizontal" id="formAdvSearch" method="post" action="{{ route('verifier.advSearchSubord',[],false) }}">      

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="empl_name">Name of Employee</label>  
  <div class="col-md-6">
  <input id="empl_name" name="empl_name" type="text" placeholder="Name of Employee" 
  class="form-control input-md">    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="empl_persno">Personnel Number</label>  
  <div class="col-md-6">
  <input id="empl_persno" name="empl_persno" type="text" placeholder="SAP Personnel Number" class="form-control input-md">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="empl_staffno">Staff No</label>  
  <div class="col-md-6">
  <input id="empl_staffno" name="empl_staffno" type="text" placeholder="Staff No" class="form-control input-md">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="empl_position">Position</label>  
  <div class="col-md-6">
  <input id="empl_position" name="empl_position" type="text" placeholder="Position" class="form-control input-md">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="empl_compcode">Company Code</label>  
  <div class="col-md-6">
  <input id="empl_compcode" name="empl_compcode" type="text" placeholder="Company Code" class="form-control input-md">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="empl_costcenter">Cost Center</label>  
  <div class="col-md-6">
  <input id="empl_costcenter" name="empl_costcenter" type="text" placeholder="ATAC07" class="form-control input-md">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="empl_personalarea">Personal Area</label>  
  <div class="col-md-6">
  <input id="empl_personalarea" name="empl_personalarea" type="text" placeholder="Personal Area" class="form-control input-md">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="empl_personalsubarea">Personal Subarea</label>  
  <div class="col-md-6">
  <input id="empl_personalsubarea" name="empl_personalsubarea" type="text" placeholder="Personal Subarea" class="form-control input-md">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="empl_subgroup">Employee Subgroup</label>  
  <div class="col-md-6">
  <input id="empl_subgroup" name="empl_subgroup" type="text" placeholder="Employee Subgroup" class="form-control input-md">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="empl_email">Email</label>  
  <div class="col-md-6">
  <input id="empl_email" name="empl_email" type="text" placeholder="Email" class="form-control input-md">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="empl_mobilenumber">Mobile Number</label>  
  <div class="col-md-6">
  <input id="empl_mobilenumber" name="empl_mobilenumber" type="text" placeholder="Mobile Number" class="form-control input-md">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="empl_officenumber">Office Number</label>  
  <div class="col-md-6">
  <input id="empl_officenumber" name="empl_officenumber" type="text" placeholder="Office Number" class="form-control input-md">
    
  </div>
</div>
<div class="form-group">
  <label class="col-md-4 control-label"></label>  
  <div class="col-md-6">
  <input id="btnAdvSearch" name="btnAdvSearch" type="button" onclick="advSearchSubmit()"
  class="btn btn-primary btn-outline" value="Search">
  <!-- <input id="btnAdvSearch" name="btnAdvSearch" type="button" onclick="advSearchSubmit()" 
  class="btn btn-primary" value="Search"> -->
  
  <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
  </div>
</div>
</form>
</fieldset>

      </div>
      <!-- <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div> -->
    </div>

  </div>
</div>


<a href="#" class="btn btn-np" onclick="testSubmit()">
      <i class="glyphicon glyphicon-search"></i> Test Sweet Alert2 input</a>
@stop

@section('js')
<script type="text/javascript">
$(document).ready(function() {

$('#verifierList').DataTable({
});
$('#verifierList2').DataTable({
});

$('.verifierListId').select2({
    placeholder: 'Type a name',
    minimumInputLength: 3,
    ajax: {
      url: '/admin/verifier/subordSearch',
      dataType: 'json',
      delay: 250,
      processResults: function (data) {
        return {
          results:  $.map(data, function (item) {
                return {
                    text: item.name+' ('+item.id+')',
                    id: item.id
                }
            })
        };
      },
      cache: true
    }
  });

} );

//when click delete group
function deletefile(id,gname){
var fid = id;
var gname = gname;

  Swal.fire({
  title: 'Group Deletion',
  html: "Are you sure want ot delete group "+gname+"?<br/>You won't be able to revert this!",
  icon: 'warning',
  showCancelButton: true,
  confirmButtonText: 'YES',
  cancelButtonText: 'NO',
  confirmButtonColor: '#d33',
  cancelButtonColor: '#3085d6',
}).then((result) => {
  if (result.value) {
    $('#fd'+fid).submit();
    Swal.fire(
      'Deleted!',
      'Your file has been deleted.',
      'success'
    )
  }
})
} //deletefile


function advSearchSubmit(){

  var dataSearch = $('#formAdvSearch').serializeArray();

  var results = dataSearch.map(function(item) {
    return item.value;
  }),
  checkInput = 0;

  $.each(results,function(){
    checkInput+=parseFloat(this.length) || 0;
  });
  //console.log(checkInput);
  if(checkInput==0){
    $("#advSearchAlertMsg").scroll();
    $("#advSearchAlertMsg").html("<div class='alert alert-warning'>Atleast has 1 input (advSearchSubmit)</div>");  
  }
  else{
    $.get("{{ route('verifier.ajaxAdvSearchSubord') }}",{dataSearch}, 
    function(data){
      //alert( "Load was performed." );
      console.log(data);   
      var imgProfile = '<img class="profile-user-img img-responsive img-circle" src="/vendor/images/useravatar.png" alt="User profile picture">';
      var html = '';
          html += '<fieldset><legend>Result Search</legend>';
      //create table
          html += '<table id="resultAdvSearchTbl" class="table"><thead>';
          html += '<th>Name</th>';
          html += '<th>Persno</th>';
          html += '<th>Staffno</th>';
          html += '<th>Sub Group</th>';
          html += '<th>Company Code</th>';
          html += '<th>Personal Area</th>';
          html += '<th>Personal Subrea</th>';
          html += '<th>Email</th>';
          html += '<th>Action</th>';
          html += '</thead><tbody>';  

        if(data.length > 0)
        {
        for(var count = 0; count < data.length; count++)
        {          
          html += '<tr>';
          html += '<td>'+data[count].id+'</td>';
          html += '<td>'+data[count].name+'</td>';
          html += '<td>'+data[count].staff_no+'</td>';
          html += '<td>'+data[count].empsgroup+'</td>';
          html += '<td>'+data[count].company_id+'</td>';
          html += '<td>'+data[count].persarea+'</td>';
          html += '<td>'+data[count].perssubarea+'</td>';
          html += '<td>'+data[count].email+'</td>';
          html += '<td><a class="btn btn-np" onclick="slctVerifier('+data[count].id+',\''+data[count].name+'\')"><i class="fas fa-user-plus"></i></a></td></tr>';
        }
      }
        else
        {
        html += '<tr><td colspan="9">No Data Found</td></tr>';
        };
        html += '</tbody></table>';  
        html += '</fieldset>';

      $("#resultAdvSearch").html(html);
      $("#advSearchAlertMsg").html("<div class='alert alert-success'>Success fetch "+data.length+" records</div>");
      if(data){
      $('#resultAdvSearchTbl').DataTable({
        "ordering": false
      });
      }      
      $('html').scrollTop(0);
      $('#modalAdvSearch').animate({ scrollTop: -10 }, 1000);      

    })//.get
  }; //if checkInput

} //advSearchSubmit

//select verifier id to selection
function slctVerifier(vid,vname){
  //alert(vname+' ('+vid+')');

  //remove existing value 
  $('#selectVerifierId').children('option:not()').remove();

  //method 1
  $('#selectVerifierId').append(
     $('<option>').val(vid).text(vname+' ('+vid+')')
     );

  //method 2
  //$('#selectVerifierId').append(new Option(vname, vid));
  
  //method 3
  // var o = new Option(vname, vid);
  // /// jquerify the DOM object 'o' so we can use the html method
  // $(o).html(vname);
  // $("#selectVerifierId").append(o);

  $('#modalAdvSearch').modal('toggle');
}

function testSubmit(){
  Swal.fire({
  title: 'Multiple inputs',
  html:
    '<input id="swal-input1" class="swal2-input">' +
    '<input id="swal-input2" class="swal2-input">',
  focusConfirm: false,
  preConfirm: () => {
    return [
      document.getElementById('swal-input1').value,
      document.getElementById('swal-input2').value
    ]
  }
})

if (formValues) {
  Swal.fire(JSON.stringify(formValues))
}

} //testSubmit
</script>
@stop