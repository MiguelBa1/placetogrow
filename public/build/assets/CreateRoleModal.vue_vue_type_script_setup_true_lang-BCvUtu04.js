import{d as x,u as b,p as y,T as C,o as P,c as V,w as r,a,f as l,t as c,e}from"./app-Bpvmybdt.js";import{_ as k}from"./InputField.vue_vue_type_script_setup_true_lang-CfQ2MUfz.js";import"./ApplicationLogo-B_getK0f.js";import{_ as m}from"./Button.vue_vue_type_script_setup_true_lang-3Poo1hdF.js";import{_ as B}from"./Modal.vue_vue_type_script_setup_true_lang-CggkL3MU.js";import"./PrimaryButton-DxnYWVKS.js";const N=x({__name:"CreateRoleModal",props:{isOpen:{type:Boolean}},emits:["closeModal"],setup(M,{emit:p}){const{t:o}=b(),d=p,n=y(),t=()=>{d("closeModal")},s=C({name:""}),u=()=>{s.post(route("roles-permissions.store"),{onSuccess:()=>{n.success(o("rolePermissions.index.create.success")),t()},onError:()=>{n.error(o("rolePermissions.index.create.error"))}})};return(_,i)=>(P(),V(e(B),{title:e(o)("rolePermissions.index.create.title"),isOpen:_.isOpen,onClose:t},{footerButtons:r(()=>[a(e(m),{type:"button",variant:"secondary",onClick:t},{default:r(()=>[l(c(e(o)("rolePermissions.index.create.cancel")),1)]),_:1}),a(e(m),{type:"button",color:"green",onClick:u,disabled:e(s).processing},{default:r(()=>[l(c(e(o)("rolePermissions.index.create.save")),1)]),_:1},8,["disabled"])]),default:r(()=>[a(e(k),{id:"name",type:"text",label:e(o)("rolePermissions.index.create.name"),modelValue:e(s).name,"onUpdate:modelValue":i[0]||(i[0]=f=>e(s).name=f),error:e(s).errors.name},null,8,["label","modelValue","error"])]),_:1},8,["title","isOpen"]))}});export{N as _};