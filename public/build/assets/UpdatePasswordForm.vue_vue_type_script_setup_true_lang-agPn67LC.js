import{d as g,u as y,m as w,T as V,o as f,h as _,b as o,t as d,e as s,a,w as v,f as b,i as x,a5 as I,g as k}from"./app-Bpvmybdt.js";import{_ as u,a as p,b as i}from"./InputLabel.vue_vue_type_script_setup_true_lang-CIDNzrCZ.js";import{P as B}from"./PrimaryButton-DxnYWVKS.js";const S={class:"text-lg font-medium text-gray-900"},h={class:"mt-1 text-sm text-gray-600"},N={class:"flex items-center gap-4"},U={key:0,class:"text-sm text-gray-600"},E=g({__name:"UpdatePasswordForm",setup(C){const{t}=y(),c=w(null),m=w(null),e=V({current_password:"",password:"",password_confirmation:""}),P=()=>{e.put(route("password.update"),{preserveScroll:!0,onSuccess:()=>{e.reset()},onError:()=>{var n,r;e.errors.password&&(e.reset("password","password_confirmation"),(n=c.value)==null||n.focus()),e.errors.current_password&&(e.reset("current_password"),(r=m.value)==null||r.focus())}})};return(n,r)=>(f(),_("section",null,[o("header",null,[o("h2",S,d(s(t)("profile.edit.updatePassword.title")),1),o("p",h,d(s(t)("profile.edit.updatePassword.description")),1)]),o("form",{onSubmit:k(P,["prevent"]),class:"mt-6 space-y-6"},[o("div",null,[a(u,{forId:"current_password",value:s(t)("profile.edit.updatePassword.currentPasswordLabel")},null,8,["value"]),a(p,{id:"current_password",ref_key:"currentPasswordInput",ref:m,modelValue:s(e).current_password,"onUpdate:modelValue":r[0]||(r[0]=l=>s(e).current_password=l),type:"password",class:"mt-1 block w-full",autocomplete:"current-password"},null,8,["modelValue"]),a(i,{message:s(e).errors.current_password,class:"mt-2"},null,8,["message"])]),o("div",null,[a(u,{forId:"password",value:s(t)("profile.edit.updatePassword.newPasswordLabel")},null,8,["value"]),a(p,{id:"password",ref_key:"passwordInput",ref:c,modelValue:s(e).password,"onUpdate:modelValue":r[1]||(r[1]=l=>s(e).password=l),type:"password",class:"mt-1 block w-full",autocomplete:"new-password"},null,8,["modelValue"]),a(i,{message:s(e).errors.password,class:"mt-2"},null,8,["message"])]),o("div",null,[a(u,{forId:"password_confirmation",value:s(t)("profile.edit.updatePassword.confirmPasswordLabel")},null,8,["value"]),a(p,{id:"password_confirmation",modelValue:s(e).password_confirmation,"onUpdate:modelValue":r[2]||(r[2]=l=>s(e).password_confirmation=l),type:"password",class:"mt-1 block w-full",autocomplete:"new-password"},null,8,["modelValue"]),a(i,{message:s(e).errors.password_confirmation,class:"mt-2"},null,8,["message"])]),o("div",N,[a(B,{disabled:s(e).processing},{default:v(()=>[b(d(s(t)("profile.edit.updatePassword.saveButton")),1)]),_:1},8,["disabled"]),a(I,{"enter-active-class":"transition ease-in-out","enter-from-class":"opacity-0","leave-active-class":"transition ease-in-out","leave-to-class":"opacity-0"},{default:v(()=>[s(e).recentlySuccessful?(f(),_("p",U,d(s(t)("profile.edit.updateProfileInformation.savedMessage")),1)):x("",!0)]),_:1})])],32)]))}});export{E as _};