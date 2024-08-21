import{d as S,p as F,u as j,T as D,j as d,m as E,q as V,o as y,h as _,a as l,w as n,b as i,t as p,e,Z as P,f as v,g as $,F as M,O as R}from"./app-Bpvmybdt.js";import{_ as A}from"./MainLayout.vue_vue_type_script_setup_true_lang-C7yXbFLh.js";import{M as b,F as L}from"./MicrositeType-Ug9L9Vje.js";import{_ as u}from"./InputField.vue_vue_type_script_setup_true_lang-CfQ2MUfz.js";import{_ as c}from"./Listbox.vue_vue_type_script_setup_true_lang-DSl_v-Rk.js";import{_ as x}from"./Button.vue_vue_type_script_setup_true_lang-3Poo1hdF.js";import"./ApplicationLogo-B_getK0f.js";import"./PrimaryButton-DxnYWVKS.js";import"./dayjs.min-DfHwKj4N.js";import"./_plugin-vue_export-helper-DlAUqK2U.js";import"./keyboard-BDkKcqIt.js";const Z={class:"flex justify-between items-center"},z={class:"font-semibold text-xl text-gray-800 leading-tight"},G={class:"col-span-2 grid grid-cols-2 gap-4"},H={key:0,class:"mt-1"},J=["src"],K={key:1,class:"mt-1"},Q=i("img",{src:"/images/placeholder.png",alt:"Placeholder",class:"w-48 h-48 object-cover rounded-md border"},null,-1),W=[Q],X={class:"col-span-2"},de=S({__name:"Create",props:{categories:{},documentTypes:{},micrositeTypes:{},currencyTypes:{}},setup(T){const g=F(),{t:s}=j(),{categories:w,documentTypes:U,micrositeTypes:q,currencyTypes:k}=T,r=D({name:"",logo:null,category_id:"",payment_currency:"",payment_expiration:"",type:"",responsible_name:"",responsible_document_number:"",responsible_document_type:""}),C=d(()=>q),O=d(()=>U),h=d(()=>k),I=d(()=>w.map(a=>({label:a.name,value:a.id}))),m=E(null);V(()=>r.logo,a=>{if(a){const o=new FileReader;o.onload=t=>{var f;m.value=(f=t.target)==null?void 0:f.result},o.readAsDataURL(a)}else m.value=null}),V(()=>r.type,a=>{a===b.BASIC&&(r.payment_expiration="")});const N=()=>{r.post(route("microsites.store"),{onSuccess:()=>{r.reset(),g.success(s("microsites.create.form.success"));const a=route().params.page||1;R.visit(route("microsites.index",{page:a}),{only:["microsites"]})},onError:()=>{g.error(s("microsites.create.form.error"))}})},B=()=>{history.back()};return(a,o)=>(y(),_(M,null,[l(e(P),null,{default:n(()=>[i("title",null,p(e(s)("microsites.create.title")),1)]),_:1}),l(e(A),null,{header:n(()=>[i("div",Z,[i("h2",z,p(e(s)("microsites.create.header")),1),l(e(x),{variant:"secondary",color:"gray",onClick:B},{default:n(()=>[v(p(e(s)("microsites.create.back")),1)]),_:1})])]),default:n(()=>[i("form",{onSubmit:$(N,["prevent"]),class:"w-full p-4 sm:p-8 bg-white shadow sm:rounded-lg grid grid-cols-2 gap-4"},[l(e(u),{id:"name",type:"text",label:e(s)("microsites.create.form.name"),modelValue:e(r).name,"onUpdate:modelValue":o[0]||(o[0]=t=>e(r).name=t),error:e(r).errors.name,required:""},null,8,["label","modelValue","error"]),l(e(c),{id:"category_id",label:e(s)("microsites.create.form.category"),modelValue:e(r).category_id,"onUpdate:modelValue":o[1]||(o[1]=t=>e(r).category_id=t),options:I.value,error:e(r).errors.category_id,required:""},null,8,["label","modelValue","options","error"]),l(e(u),{id:"responsible_name",type:"text",label:e(s)("microsites.create.form.responsibleName"),modelValue:e(r).responsible_name,"onUpdate:modelValue":o[2]||(o[2]=t=>e(r).responsible_name=t),error:e(r).errors.responsible_name,required:""},null,8,["label","modelValue","error"]),l(e(c),{id:"responsible_document_type",label:e(s)("microsites.create.form.responsibleDocumentType"),modelValue:e(r).responsible_document_type,"onUpdate:modelValue":o[3]||(o[3]=t=>e(r).responsible_document_type=t),options:O.value,error:e(r).errors.responsible_document_type,required:""},null,8,["label","modelValue","options","error"]),l(e(u),{id:"responsible_document_number",type:"text",label:e(s)("microsites.create.form.responsibleDocumentNumber"),modelValue:e(r).responsible_document_number,"onUpdate:modelValue":o[4]||(o[4]=t=>e(r).responsible_document_number=t),error:e(r).errors.responsible_document_number,required:""},null,8,["label","modelValue","error"]),l(e(c),{id:"payment_currency",label:e(s)("microsites.create.form.paymentCurrency"),modelValue:e(r).payment_currency,"onUpdate:modelValue":o[5]||(o[5]=t=>e(r).payment_currency=t),options:h.value,error:e(r).errors.payment_currency,required:""},null,8,["label","modelValue","options","error"]),l(e(c),{id:"type",label:e(s)("microsites.create.form.type"),modelValue:e(r).type,"onUpdate:modelValue":o[6]||(o[6]=t=>e(r).type=t),options:C.value,error:e(r).errors.type,required:""},null,8,["label","modelValue","options","error"]),l(e(u),{id:"payment_expiration",type:"number",min:1,max:365,label:e(s)("microsites.create.form.paymentExpiration"),modelValue:e(r).payment_expiration,"onUpdate:modelValue":o[7]||(o[7]=t=>e(r).payment_expiration=t),error:e(r).errors.payment_expiration,disabled:![e(b).SUBSCRIPTION,e(b).INVOICE].includes(e(r).type)},null,8,["label","modelValue","error","disabled"]),i("div",G,[l(e(L),{id:"logo",label:e(s)("microsites.create.form.logo"),modelValue:e(r).logo,"onUpdate:modelValue":o[8]||(o[8]=t=>e(r).logo=t),error:e(r).errors.logo,accept:"image/*",required:""},null,8,["label","modelValue","error"]),m.value?(y(),_("div",H,[i("img",{src:m.value,alt:"Preview",class:"w-48 h-48 object-cover rounded-md border"},null,8,J)])):(y(),_("div",K,W))]),i("div",X,[l(e(x),{type:"submit",variant:"primary",color:"blue",disabled:e(r).processing},{default:n(()=>[v(p(e(s)("microsites.create.form.save")),1)]),_:1},8,["disabled"])])],32)]),_:1})],64))}});export{de as default};