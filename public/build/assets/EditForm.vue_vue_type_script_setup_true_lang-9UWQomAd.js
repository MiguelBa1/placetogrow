import{d as C,p as S,u as B,T as E,j as m,m as k,q as f,o as c,h as y,a as n,e,b as u,w as D,f as F,t as j,g as M,O as P}from"./app-Bpvmybdt.js";import{M as b,F as R}from"./MicrositeType-Ug9L9Vje.js";import{_ as d}from"./InputField.vue_vue_type_script_setup_true_lang-CfQ2MUfz.js";import{_ as p}from"./Listbox.vue_vue_type_script_setup_true_lang-DSl_v-Rk.js";import{_ as $}from"./Button.vue_vue_type_script_setup_true_lang-3Poo1hdF.js";import"./ApplicationLogo-B_getK0f.js";import"./PrimaryButton-DxnYWVKS.js";import"./dayjs.min-DfHwKj4N.js";const A={class:"flex flex-col md:col-span-2 md:grid md:grid-cols-2 gap-4"},L={key:0,class:"mt-1"},h=["src","alt"],z={key:1,class:"mt-1"},G=u("img",{src:"/images/placeholder.png",alt:"Placeholder",class:"w-48 h-48 object-cover rounded-md border"},null,-1),H=[G],J={class:"col-span-2"},re=C({__name:"EditForm",props:{microsite:{},categories:{},documentTypes:{},micrositeTypes:{},currencyTypes:{}},setup(V){const _=S(),{t}=B(),{microsite:l,categories:v,documentTypes:x,micrositeTypes:T,currencyTypes:U}=V,o=E({name:l.name,logo:null,category_id:l.category_id,payment_currency:l.payment_currency,payment_expiration:l.payment_expiration??void 0,type:l.type,responsible_name:l.responsible_name,responsible_document_number:l.responsible_document_number,responsible_document_type:l.responsible_document_type}),w=m(()=>T),q=m(()=>x),O=m(()=>U),I=m(()=>v.map(i=>({label:i.name,value:i.id}))),a=k(l.logo);f(()=>o.logo,i=>{if(i){const r=new FileReader;r.onload=s=>{var g;a.value=(g=s.target)==null?void 0:g.result},r.readAsDataURL(i)}else a.value=l.logo}),f(()=>o.type,i=>{i===b.BASIC&&(o.payment_expiration=void 0)});const N=()=>{o.post(route("microsites.update",l),{onSuccess:()=>{_.success(t("microsites.edit.form.success"));const i=route().params.page||1;P.visit(route("microsites.index",{page:i}),{only:["microsites"]})},onError:()=>{_.error(t("microsites.edit.form.error"))}})};return(i,r)=>(c(),y("form",{onSubmit:M(N,["prevent"]),class:"w-full p-4 sm:p-8 bg-white shadow sm:rounded-lg grid grid-cols-2 gap-4"},[n(e(d),{id:"name",type:"text",label:e(t)("microsites.edit.form.name"),modelValue:e(o).name,"onUpdate:modelValue":r[0]||(r[0]=s=>e(o).name=s),error:e(o).errors.name,required:""},null,8,["label","modelValue","error"]),n(e(p),{id:"category_id",label:e(t)("microsites.edit.form.category"),modelValue:e(o).category_id,"onUpdate:modelValue":r[1]||(r[1]=s=>e(o).category_id=s),options:I.value,error:e(o).errors.category_id,required:""},null,8,["label","modelValue","options","error"]),n(e(d),{id:"responsible_name",type:"text",label:e(t)("microsites.edit.form.responsibleName"),modelValue:e(o).responsible_name,"onUpdate:modelValue":r[2]||(r[2]=s=>e(o).responsible_name=s),error:e(o).errors.responsible_name,required:""},null,8,["label","modelValue","error"]),n(e(p),{id:"responsible_document_type",label:e(t)("microsites.edit.form.responsibleDocumentType"),modelValue:e(o).responsible_document_type,"onUpdate:modelValue":r[3]||(r[3]=s=>e(o).responsible_document_type=s),options:q.value,error:e(o).errors.responsible_document_type,required:""},null,8,["label","modelValue","options","error"]),n(e(d),{id:"responsible_document_number",type:"text",label:e(t)("microsites.edit.form.responsibleDocumentNumber"),modelValue:e(o).responsible_document_number,"onUpdate:modelValue":r[4]||(r[4]=s=>e(o).responsible_document_number=s),error:e(o).errors.responsible_document_number,required:""},null,8,["label","modelValue","error"]),n(e(p),{id:"payment_currency",label:e(t)("microsites.edit.form.paymentCurrency"),modelValue:e(o).payment_currency,"onUpdate:modelValue":r[5]||(r[5]=s=>e(o).payment_currency=s),options:O.value,error:e(o).errors.payment_currency,required:""},null,8,["label","modelValue","options","error"]),n(e(p),{id:"type",label:e(t)("microsites.edit.form.type"),modelValue:e(o).type,"onUpdate:modelValue":r[6]||(r[6]=s=>e(o).type=s),options:w.value,error:e(o).errors.type,required:""},null,8,["label","modelValue","options","error"]),n(e(d),{id:"payment_expiration",type:"number",label:e(t)("microsites.edit.form.paymentExpiration"),modelValue:e(o).payment_expiration,"onUpdate:modelValue":r[7]||(r[7]=s=>e(o).payment_expiration=s),error:e(o).errors.payment_expiration,disabled:![e(b).SUBSCRIPTION,e(b).INVOICE].includes(e(o).type)},null,8,["label","modelValue","error","disabled"]),u("div",A,[n(e(R),{id:"logo",label:e(t)("microsites.edit.form.logo"),modelValue:e(o).logo,"onUpdate:modelValue":r[8]||(r[8]=s=>e(o).logo=s),error:e(o).errors.logo,accept:"image/*"},null,8,["label","modelValue","error"]),a.value?(c(),y("div",L,[u("img",{src:a.value,alt:e(o).name+" Logo",class:"w-48 h-48 object-cover rounded-md border"},null,8,h)])):(c(),y("div",z,H))]),u("div",J,[n(e($),{type:"submit",variant:"primary",color:"blue",disabled:e(o).processing},{default:D(()=>[F(j(e(t)("microsites.edit.form.save")),1)]),_:1},8,["disabled"])])],32))}});export{re as _};