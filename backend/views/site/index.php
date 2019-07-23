<?php
use yii\helpers\Url;


$base = Url::base();

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="row">
        <h1>Consultas a la BD</h1>
        <p>Consultas para verificación del API sobre la BD</p>
        <p>
            <buton class="btn btn-info" onclick="ajaxGetTables()">
                Obtener tablas
            </button>
        </p>
    </div>

    <div class="body-content">
        <div class="row">

        </div>
    </div>
</div>

<script>
    var options = "<option value='-1'>-- SELECCIONE --</option>"+
                  "<option value='\='>igual que</option>"+
                  "<option value='like'>parecido a</option>"+
                  "<option value='\>'>mayor que</option>"+
                  "<option value='\>='>mayor o igual que</option>"+
                  "<option value='\<'>menor que</option>"+
                  "<option value='\<='>menor o igual que</option>"+
                  "<option value='\<>'>diferente de</option>";
    function ajaxGetTables(){
        $.ajax({
            url: '<?= $base ?>/index.php?r=site/gettables',
            success: function(tables) {
                $('.body-content .row').html('<ul id="data-list"></ul>');
                for(i = 0; i < tables.length; i++){
                    let table = '<li><input class="table-option" type="checkbox" name="'+tables[i][Object.keys(tables[i])[0]]+'" id="'+tables[i][Object.keys(tables[i])[0]]+'"> '+tables[i][Object.keys(tables[i])[0]]+'</li>';
                    $('#data-list').append(table);
                }

                $('#data-list').append('<button class="btn btn-info" id="get_fields" onclick="ajaxGetFields()">Obtener campos de las tablas seleccionadas</button>');
            },
            error: function() {
                console.log("No se ha podido obtener la información");
            }
        });
    }
    function ajaxGetFields(){
        let table_list = new Array();
        $('.table-option')
            .each(function(index) { 
                $(this).prop('checked') ? table_list.push($(this).attr('id')) : null 
            });

        $.ajax({
            url: '<?= $base ?>/index.php?r=site/getfields',
            data: {
                table_list: table_list
            },
            method: 'post',
            success: function(tables) {
                $('#data-list').html('');
                for(let i = 0; i < tables.length; i++){
                    $('#data-list').append('<li><span>'+tables[i]['table_name']+'</span><table id="'+tables[i]['table_name']+'" class="table table_description"></table></li>');
                    for (let j = 0; j < tables[i]['fields'].length; j++) {
                        $('#'+tables[i]['table_name'])
                            .append('<tr>'+
                                        '<td><input type="checkbox" name="'+tables[i]['fields'][j].Field+'"> '+tables[i]['fields'][j].Field+'</td>'+
                                        '<td><select name="select_'+tables[i]['fields'][j].Field+'" id="'+tables[i]['fields'][j].Field+'">'+options+'</select></td>'+
                                        '<td><input type="text" name="input_'+tables[i]['fields'][j].Field+'"></td>'+
                                    '</tr>');
                    }
                }

                $('#data-list').append('<button class="btn btn-info" id="get_fields" onclick="ajaxGetData()">Obtener datos</button>');
            },
            error: function() {
                console.log("No se ha podido obtener la información");
            }
        });
    }
    function ajaxGetData(){
        let table_list = new Array();
        var table;
        $('.table_description')
            .each(function(index) { 
                table = new Array();
                table.push($(this).attr('id'));
                table.push(new Array());
                
                $(this).children()
                    .each(function(index) { 
                        console.log($(this).children());
                        let row = $(this).children('td');
                        row.children('input[type=checkbox]').prop('checked') 
                            ? table[1]
                                .push(new Array(
                                    row.children('input[type=checkbox]').attr('name'), 
                                    row.children('select').val(), 
                                    row.children('input[type=text]').val()
                                ))
                            : null 
                    });
                table_list.push(table)
            });

        console.log(table_list);
        
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
    }
</script>