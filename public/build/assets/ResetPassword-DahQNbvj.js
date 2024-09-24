import{d as f,u as _,T as b,o as g,c as v,w as d,a,b as l,t as p,e as s,Z as V,f as h,n as P,g as k}from"./app-BIJ4Abzx.js";import"./ApplicationLogo-DROgA_Il.js";import{_ as m,a as n,b as i}from"./InputLabel.vue_vue_type_script_setup_true_lang-CW3Wcmgv.js";import{P as x}from"./PrimaryButton-D0pUaKYk.js";import{_ as y}from"./MainLayout.vue_vue_type_script_setup_true_lang-C15JnSKh.js";import"./_plugin-vue_export-helper-DlAUqK2U.js";const B={class:"mt-4"},I={class:"mt-4"},$={class:"flex items-center justify-end mt-4"},j=f({__name:"ResetPassword",props:{email:{},token:{}},setup(c){const{t}=_(),u=c,e=b({token:u.token,email:u.email,password:"",password_confirmation:""}),w=()=>{e.post(route("password.store"),{onFinish:()=>{e.reset("password","password_confirmation")}})};return(q,o)=>(g(),v(s(y),null,{default:d(()=>[a(s(V),null,{default:d(()=>[l("title",null,p(s(t)("auth.resetPassword.title")),1)]),_:1}),l("form",{onSubmit:k(w,["prevent"]),class:"w-full mx-auto sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg"},[l("div",null,[a(s(m),{forId:"email",value:s(t)("auth.resetPassword.emailLabel")},null,8,["value"]),a(s(n),{id:"email",type:"email",class:"mt-1 block w-full",modelValue:s(e).email,"onUpdate:modelValue":o[0]||(o[0]=r=>s(e).email=r),required:"",autofocus:"",autocomplete:"username"},null,8,["modelValue"]),a(s(i),{class:"mt-2",message:s(e).errors.email},null,8,["message"])]),l("div",B,[a(s(m),{forId:"password",value:s(t)("auth.resetPassword.passwordLabel")},null,8,["value"]),a(s(n),{id:"password",type:"password",class:"mt-1 block w-full",modelValue:s(e).password,"onUpdate:modelValue":o[1]||(o[1]=r=>s(e).password=r),required:"",autocomplete:"new-password"},null,8,["modelValue"]),a(s(i),{class:"mt-2",message:s(e).errors.password},null,8,["message"])]),l("div",I,[a(s(m),{forId:"password_confirmation",value:s(t)("auth.resetPassword.confirmPasswordLabel")},null,8,["value"]),a(s(n),{id:"password_confirmation",type:"password",class:"mt-1 block w-full",modelValue:s(e).password_confirmation,"onUpdate:modelValue":o[2]||(o[2]=r=>s(e).password_confirmation=r),required:"",autocomplete:"new-password"},null,8,["modelValue"]),a(s(i),{class:"mt-2",message:s(e).errors.password_confirmation},null,8,["message"])]),l("div",$,[a(s(x),{class:P({"opacity-25":s(e).processing}),disabled:s(e).processing},{default:d(()=>[h(p(s(t)("auth.resetPassword.resetButton")),1)]),_:1},8,["class","disabled"])])],32)]),_:1}))}});export{j as default};