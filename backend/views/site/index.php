<?php
use yii\helpers\Url;


$base = Url::base();

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="row">
        <div class="col-md-8">
            <h1>Consultas a la BD</h1>
            <p>Consultas para verificación del API sobre la BD</p>
        </div>
        <div class="col-md-4">
            <p>
                <button class="btn btn-info" onclick="addTable()">
                    Agregar tabla
                </button>
                <button class="btn btn-info" onclick="ajaxGetData()" disabled>
                    Consultar Data
                </button>
            </p>
        </div>
    </div>

    <div class="body-content">
        <div class="row col-md-12">
            <div class="table-selector"></div>
        </div>
    </div>
</div>



<script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>
<script>
    var table_index = 0;
    var options = "<option value='-1'>-- SELECCIONE --</option>"+
                  "<option value='\='>igual que</option>"+
                  "<option value='like'>parecido a</option>"+
                  "<option value='\>'>mayor que</option>"+
                  "<option value='\>='>mayor o igual que</option>"+
                  "<option value='\<'>menor que</option>"+
                  "<option value='\<='>menor o igual que</option>"+
                  "<option value='\<>'>diferente de</option>";

    var tables_array = [];
    var tableList = [];

    $(function(){
        ajaxGetTables();
    });

    function addTable(){
        //console.log(tables_array);
        $('.table-selector').append(''+
            '<div class="row col-md-12 mb-3 table_container table_'+table_index+'">'+
                '<select class="table_selected" name="table_'+table_index+'" id="table_'+table_index+'" onchange="ajaxGetFields('+table_index+')">'+
                '</select>'+
                '<div class="table_field_list">'+
                '</div>'+
            '</div>');
        $.each(tables_array, function(index, value) {
            $('#table_'+table_index).append(value['table_option']);
        });
        table_index++;
        
    }

    function ajaxGetTables(){
        $.ajax({
            url: '<?= $base ?>/index.php?r=site/gettables',
            success: function(tables) {
                tables_array.push( { table_option: '<option class="table-option" type="checkbox" name="-1" id="-1">-- SELECCIONE --</option>' });
                //console.log(tables);
                for(i = 0; i < tables.length; i++){
                    let table_name = tables[i][Object.keys(tables[i])[0]];
                    tables_array.push( { table_option: '<option class="table-option" type="checkbox" name="'+tables[i][Object.keys(tables[i])[0]]+'" id="'+tables[i][Object.keys(tables[i])[0]]+'"> '+tables[i][Object.keys(tables[i])[0]]+'</option>' });
                }
            },
            error: function() {
                console.log("No se ha podido obtener la información de las tablas");
            }
        });
    }
    function ajaxGetFields(table_index_parameter){
        tableList.push({table_name: $('#table_'+table_index_parameter).val(),  table_fields: [], table_selected_fields: [], table_constraints: []});
        let table_list = [$('#table_'+table_index_parameter).val()];
        $.ajax({
            url: '<?= $base ?>/index.php?r=site/getfields',
            data: {
                table_list: table_list
            },
            method: 'post',
            success: function(tables) {
                $('.table_'+table_index_parameter+' .table_field_list').html(''+
                    '<div class="col-md-12 row">'+
                        '<div class="col-md-3 row fields">'+
                            '<h4>Campos a traer</h4>'+
                            '<button class="btn btn-success btn-sm w-100" onclick="addFieldToSelect('+table_index_parameter+')">Agregar Campo</button>'+
                            '<div class="col-md-12 fields_list">'+
                            '</div>'+       
                        '</div>'+
                        '<div class="col-md-9 constraints">'+
                            '<h4>Condiciones de Búsqueda</h4>'+
                            '<button class="btn btn-success btn-sm w-100" onclick="addConstraintToTable('+table_index_parameter+')">Agregar Condición</button>'+
                            '<div class="col-md-12 constraints_list">'+
                            '</div>'+  
                        '</div>'+
                    '</div>');
                tableList[table_index_parameter]['table_fields'] = [];
                for(let i = 0; i < tables[0]['fields'].length; i++){
                    tableList[table_index_parameter]['table_fields'].push(
                        '<option class="table-option" type="checkbox" name="'+tables[0]['fields'][i].Field+'" id="'+tables[0]['fields'][i].Field+'"> '+tables[0]['fields'][i].Field+'</option>'
                    );
                }

                //$('#data-list').append('<button class="btn btn-info" id="get_fields" onclick="ajaxGetData()">Obtener datos</button>');
            },
            error: function() {
                console.log("No se ha podido obtener la informatable_ción");
            }
        });
    }

    function addFieldToSelect(table_index_parameter){
        $('.table_'+table_index_parameter+' .table_field_list .fields .fields_list').append('<select class="field mt-3" id="" name=""></select><hr>');
        $.each(tableList[table_index_parameter]['table_fields'], function(index) {
            $('.table_'+table_index_parameter+' .table_field_list .fields .fields_list .field').last().append(tableList[table_index_parameter]['table_fields'][index]);
        });
    }

    function addConstraintToTable(table_index_parameter){
        var table_list_for_render = '';
        $.each(tables_array, function(index, value) {
            //console.log(value['table_option']);
            table_list_for_render += value['table_option'];
        });

        var table_fields_for_render = '';
        $.each(tableList[table_index_parameter]['table_fields'], function(index, value) {
            table_fields_for_render += value;
        });

        $('.table_'+table_index_parameter+' .table_field_list .constraints .constraints_list').append('<div class="constraint mt-3">'+
                '<select class="field col-md-4">'+table_fields_for_render+'</select>'+
                //'<select cass="type" id="" name="" onchange="changeTypeOfConrtaint"><option value="tabla">tabla</option><option value="valor">valor</option></select>'+
                '<select class="options col-md-4">'+options+'</select>'+
                '<input class="value col-md-4" type="text"/>'+
                //'<select class="tables">'+table_list_for_render+'</select>'+
            '</div><hr>');
    }

    function ajaxGetData(){
        let table_list = [];
        var table;
        /*
        $.each($('.table_container'), function(index){
            table_list.push($(this).children('.table_selected').val());
        });
        
        console.log(table_list);
        /*

        /***/
        $.each($('.table_container'), function(index) { 
                table = [];
                table.push($(this).children('.table_selected').val());
                tableFieldList = [];
                tableConstraintList = [];
                
                $.each( $(this).find('.fields_list .field'), function(index) { 
                        tableFieldList.push($(this).val());
                    });
                
                $.each($(this).find('.constraints_list .constraint'), function(index) { 
                        tableConstraintList.push([
                            $(this).children('.field').val(), 
                            $(this).children('.options').val(), 
                            $(this).children('.options').val() == 'like' ? '%'+$(this).children('.value').val()+'%' : $(this).children('.value').val()
                        ]);
                    });

                table.push(tableFieldList);
                table.push(tableConstraintList);

                table_list.push(table)
            });

        console.log(table_list);
        /* */
        $.ajax({
            url: '<?= $base ?>/index.php?r=site/getdata',
            data: {
                table_list: table_list
            },
            method: 'post',
            success: function(tables) {
                $('#data-list').html('');
                for(i = 0; i < tables.length; i++){
                    $('#data-list').append('<pre>'+tables[i]+'</pre>');
                }
            },
            error: function() {
                console.log("No se ha podido obtener la información");
            }
        });
        /** */
    }

    
</script>