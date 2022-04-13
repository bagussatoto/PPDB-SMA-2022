
Runner.buttonEvents["DAFTAR"]=function(pageObj,proxy,pageid){pageObj.buttonNames[pageObj.buttonNames.length]='DAFTAR';if(!pageObj.buttonEventBefore['DAFTAR']){pageObj.buttonEventBefore['DAFTAR']=function(params,ctrl,pageObj,proxy,pageid,rowData,row,submit){ctrl.setEnabled();location.href='./calonppdb_add.php';}}
if(!pageObj.buttonEventAfter['DAFTAR']){pageObj.buttonEventAfter['DAFTAR']=function(result,ctrl,pageObj,proxy,pageid,rowData,row,params){var message=result["txt"]+" !!!";ctrl.setMessage(message);}}
$('a[id="DAFTAR"]').each(function(){if($(this).closest('.gridRowAdd').length){return;}
this.id="DAFTAR"+"_"+Runner.genId();var button_DAFTAR=new Runner.form.Button({id:this.id,btnName:"DAFTAR"});button_DAFTAR.init({args:[pageObj,proxy,pageid]});});};