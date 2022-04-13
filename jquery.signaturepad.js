
(function($){function SignaturePad(selector,options){var self=this,settings=$.extend({},$.fn.signaturePad.defaults,options),context=$(selector),canvas=$(settings.canvas,context),element=canvas.get(0),canvasContext=null,previous={'x':null,'y':null},output=[],mouseLeaveTimeout=false,touchable=false,eventsBound=false
function drawLine(e,newYOffset){var offset=$(e.target).offset(),newX,newY
clearTimeout(mouseLeaveTimeout)
mouseLeaveTimeout=false
if(typeof e.changedTouches!=='undefined'){newX=Math.floor(e.changedTouches[0].pageX-offset.left)
newY=Math.floor(e.changedTouches[0].pageY-offset.top)}else{newX=Math.floor(e.pageX-offset.left)
newY=Math.floor(e.pageY-offset.top)}
if(previous.x===newX&&previous.y===newY)
return true
if(previous.x===null)
previous.x=newX
if(previous.y===null)
previous.y=newY
if(newYOffset)
newY+=newYOffset
canvasContext.beginPath()
canvasContext.moveTo(previous.x,previous.y)
canvasContext.lineTo(newX,newY)
canvasContext.lineCap=settings.penCap
canvasContext.stroke()
canvasContext.closePath()
output.push({'lx':newX,'ly':newY,'mx':previous.x,'my':previous.y})
previous.x=newX
previous.y=newY
if(settings.onDraw&&typeof settings.onDraw==='function')
settings.onDraw.apply(self)}
function stopDrawing(){if(touchable){canvas.each(function(){this.removeEventListener('touchmove',drawLine)})}else{canvas.unbind('mousemove.signaturepad')}
if(output.length>0&&settings.onDrawEnd&&typeof settings.onDrawEnd==='function')
settings.onDrawEnd.apply(self)
previous.x=null
previous.y=null
if(settings.output&&output.length>0)
$(settings.output,context).val(JSON.stringify(output))}
function drawSigLine(){if(!settings.lineWidth)
return false
canvasContext.beginPath()
canvasContext.lineWidth=settings.lineWidth
canvasContext.strokeStyle=settings.lineColour
canvasContext.moveTo(settings.lineMargin,settings.lineTop)
canvasContext.lineTo(element.width-settings.lineMargin,settings.lineTop)
canvasContext.stroke()
canvasContext.closePath()}
function clearCanvas(){stopDrawing()
canvasContext.clearRect(0,0,element.width,element.height)
canvasContext.fillStyle="transparent"
canvasContext.fillRect(0,0,element.width,element.height)
canvasContext.lineWidth=settings.penWidth
canvasContext.strokeStyle=settings.penColour
$(settings.output,context).val('')
output=[]}
function startDrawing(e,o){if(touchable){canvas.each(function(){this.addEventListener('touchmove',drawLine,false)})}else{canvas.bind('mousemove.signaturepad',drawLine)}
drawLine(e,1)}
function disableCanvas(){eventsBound=false
canvas.each(function(){if(this.removeEventListener){this.removeEventListener('touchend',stopDrawing)
this.removeEventListener('touchcancel',stopDrawing)
this.removeEventListener('touchmove',drawLine)}
if(this.ontouchstart)
this.ontouchstart=null;})
canvas.unbind('mousedown.signaturepad')
canvas.unbind('mouseup.signaturepad')
canvas.unbind('mousemove.signaturepad')
canvas.unbind('mouseleave.signaturepad')
$(settings.clear,context).unbind('click.signaturepad')}
function initDrawEvents(e){if(eventsBound)
return false
eventsBound=true
$('input').blur();if(typeof e.changedTouches!=='undefined')
touchable=true
if(touchable){canvas.each(function(){this.addEventListener('touchend',stopDrawing,false)
this.addEventListener('touchcancel',stopDrawing,false)})
canvas.unbind('mousedown.signaturepad')}else{canvas.bind('mouseup.signaturepad',function(e){stopDrawing()})
canvas.bind('mouseleave.signaturepad',function(e){if(!mouseLeaveTimeout){mouseLeaveTimeout=setTimeout(function(){stopDrawing()
clearTimeout(mouseLeaveTimeout)
mouseLeaveTimeout=false},500)}})
canvas.each(function(){this.ontouchstart=null})}}
function drawIt(){$(settings.typed,context).hide()
clearCanvas()
canvas.each(function(){this.addEventListener('touchstart',function(e){e.preventDefault()
initDrawEvents(e)
startDrawing(e,this)});})
canvas.bind('mousedown.signaturepad',function(e){initDrawEvents(e)
startDrawing(e,this)})
$(settings.clear,context).bind('click.signaturepad',function(e){e.preventDefault();clearCanvas()})
$(settings.typeIt,context).bind('click.signaturepad',function(e){e.preventDefault();typeIt()})
$(settings.drawIt,context).unbind('click.signaturepad')
$(settings.drawIt,context).bind('click.signaturepad',function(e){e.preventDefault()})
$(settings.typeIt,context).removeClass(settings.currentClass)
$(settings.drawIt,context).addClass(settings.currentClass)
$(settings.sig,context).addClass(settings.currentClass)
$(settings.typeItDesc,context).hide()
$(settings.drawItDesc,context).show()
$(settings.clear,context).show()}
function typeIt(){clearCanvas()
disableCanvas()
$(settings.typed,context).show()
$(settings.drawIt,context).bind('click.signaturepad',function(e){e.preventDefault();drawIt()})
$(settings.typeIt,context).unbind('click.signaturepad')
$(settings.typeIt,context).bind('click.signaturepad',function(e){e.preventDefault()})
$(settings.output,context).val('')
$(settings.drawIt,context).removeClass(settings.currentClass)
$(settings.typeIt,context).addClass(settings.currentClass)
$(settings.sig,context).removeClass(settings.currentClass)
$(settings.drawItDesc,context).hide()
$(settings.clear,context).hide()
$(settings.typeItDesc,context).show()}
function type(val){$(settings.typed,context).html(val.replace(/>/g,'&gt;').replace(/</g,'&lt;'))
while($(settings.typed,context).width()>element.width){var oldSize=$(settings.typed,context).css('font-size').replace(/px/,'')
$(settings.typed,context).css('font-size',oldSize-1+'px')}}
function onBeforeValidate(context,settings){$('p.'+settings.errorClass,context).remove()
context.removeClass(settings.errorClass)
$('input, label',context).removeClass(settings.errorClass)}
function onFormError(errors,context,settings){if(errors.nameInvalid){context.prepend(['<p class="',settings.errorClass,'">',settings.errorMessage,'</p>'].join(''))
$(settings.name,context).focus()
$(settings.name,context).addClass(settings.errorClass)
$('label[for='+$(settings.name).attr('id')+']',context).addClass(settings.errorClass)}
if(errors.drawInvalid)
context.prepend(['<p class="',settings.errorClass,'">',settings.errorMessageDraw,'</p>'].join(''))}
function validateForm(){var valid=true,errors={drawInvalid:false,nameInvalid:false},onBeforeArguments=[context,settings],onErrorArguments=[errors,context,settings]
if(settings.onBeforeValidate&&typeof settings.onBeforeValidate==='function'){settings.onBeforeValidate.apply(self,onBeforeArguments)}else{onBeforeValidate.apply(self,onBeforeArguments)}
if(settings.drawOnly&&output.length<1){errors.drawInvalid=true
valid=false}
if($(settings.name,context).val()===''){errors.nameInvalid=true
valid=false}
if(settings.onFormError&&typeof settings.onFormError==='function'){settings.onFormError.apply(self,onErrorArguments)}else{onFormError.apply(self,onErrorArguments)}
return valid}
function drawSignature(paths,context,saveOutput){for(var i in paths){if(typeof paths[i]==='object'){context.beginPath()
context.moveTo(paths[i].mx,paths[i].my)
context.lineTo(paths[i].lx,paths[i].ly)
context.lineCap=settings.penCap
context.stroke()
context.closePath()
if(saveOutput){output.push({'lx':paths[i].lx,'ly':paths[i].ly,'mx':paths[i].mx,'my':paths[i].my})}}}}
function init(){settings.lineTop=Math.round(element.height*7/11);if(parseFloat(((/CPU.+OS ([0-9_]{3}).*AppleWebkit.*Mobile/i.exec(navigator.userAgent))||[0,'4_2'])[1].replace('_','.'))<4.1){$.fn.Oldoffset=$.fn.offset;$.fn.offset=function(){var result=$(this).Oldoffset()
result.top-=window.scrollY
result.left-=window.scrollX
return result}}
$(settings.typed,context).bind('selectstart.signaturepad',function(e){return $(e.target).is(':input')})
canvas.bind('selectstart.signaturepad',function(e){return $(e.target).is(':input')})
if(!element.getContext&&FlashCanvas)
FlashCanvas.initElement(element)
if(element.getContext){canvasContext=element.getContext('2d')
$(settings.sig,context).show()
if(!settings.displayOnly){if(!settings.drawOnly){$(settings.name,context).bind('keyup.signaturepad',function(){type($(this).val())})
$(settings.name,context).bind('blur.signaturepad',function(){type($(this).val())})
$(settings.drawIt,context).bind('click.signaturepad',function(e){e.preventDefault()
drawIt()})}
if(settings.drawOnly||settings.defaultAction==='drawIt'){drawIt()}else{typeIt()}
if(settings.validateFields){if($(selector).is('form')){$(selector).bind('submit.signaturepad',function(){return validateForm()})}else{$(selector).parents('form').bind('submit.signaturepad',function(){return validateForm()})}}
$(settings.sigNav,context).show()}}}
$.extend(self,{init:function(){init()},updateOptions:function(options){$.extend(settings,options)},regenerate:function(paths){self.clearCanvas()
$(settings.typed,context).hide()
if(typeof paths==='string')
paths=JSON.parse(paths)
drawSignature(paths,canvasContext,true)
if(settings.output&&$(settings.output,context).length>0)
$(settings.output,context).val(JSON.stringify(output))},clearCanvas:function(){clearCanvas()},getSignature:function(){return output},getSignatureString:function(){return JSON.stringify(output)},getSignatureImage:function(){var tmpCanvas=document.createElement('canvas'),tmpContext=null,data=null
tmpCanvas.style.position='absolute'
tmpCanvas.style.top='-999em'
tmpCanvas.width=element.width
tmpCanvas.height=element.height
document.body.appendChild(tmpCanvas)
if(!tmpCanvas.getContext&&FlashCanvas)
FlashCanvas.initElement(tmpCanvas)
tmpContext=tmpCanvas.getContext('2d')
tmpContext.fillStyle=settings.bgColour
tmpContext.fillRect(0,0,element.width,element.height)
tmpContext.lineWidth=settings.penWidth
tmpContext.strokeStyle=settings.penColour
drawSignature(output,tmpContext)
data=tmpCanvas.toDataURL.apply(tmpCanvas,arguments)
document.body.removeChild(tmpCanvas)
tmpCanvas=null
return data},validateForm:function(){return validateForm()}})}
$.fn.signaturePad=function(options){var api=null
this.each(function(){if(!$.data(this,'plugin-signaturePad')){api=new SignaturePad(this,options)
api.init()
$.data(this,'plugin-signaturePad',api)}else{api=$.data(this,'plugin-signaturePad')
api.updateOptions(options)}})
return api}
$.fn.signaturePad.defaults={defaultAction:'typeIt',displayOnly:false,drawOnly:false,canvas:'canvas',sig:'.sig',sigNav:'.sigNav',bgColour:'#ffffff',penColour:'#145394',penWidth:2,penCap:'round',lineColour:'#ccc',lineWidth:2,lineMargin:5,lineTop:35,name:'.name',typed:'.typed',clear:'.clearButton',typeIt:'.typeIt a',drawIt:'.drawIt a',typeItDesc:'.typeItDesc',drawItDesc:'.drawItDesc',output:'.output',currentClass:'current',validateFields:true,errorClass:'error',errorMessage:'Please enter your name',errorMessageDraw:'Please sign the document',onBeforeValidate:null,onFormError:null,onDraw:null,onDrawEnd:null}}(jQuery))