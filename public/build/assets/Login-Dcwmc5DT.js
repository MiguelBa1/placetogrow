import{d as b,j as x,k as y,v as V,o as d,h as v,u as B,T as C,c as p,w as m,a as o,b as r,t as i,e,Z as $,i as f,l as P,f as g,n as N,g as U}from"./app-Bpvmybdt.js";import"./ApplicationLogo-B_getK0f.js";import{_ as h,a as _,b as w}from"./InputLabel.vue_vue_type_script_setup_true_lang-CIDNzrCZ.js";import{P as q}from"./PrimaryButton-DxnYWVKS.js";import{_ as I}from"./MainLayout.vue_vue_type_script_setup_true_lang-C7yXbFLh.js";import"./_plugin-vue_export-helper-DlAUqK2U.js";const L=["value"],M=b({__name:"Checkbox",props:{checked:{type:Boolean},value:{}},emits:["update:checked"],setup(c,{emit:a}){const s=a,u=c,n=x({get(){return u.checked},set(t){s("update:checked",t)}});return(t,l)=>y((d(),v("input",{type:"checkbox",value:t.value,"onUpdate:modelValue":l[0]||(l[0]=k=>n.value=k),class:"rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"},null,8,L)),[[V,n.value]])}}),j={key:0,class:"mb-4 font-medium text-sm text-green-600"},D={class:"mt-4"},R={class:"block mt-4"},S={for:"remember",class:"flex items-center"},T={class:"ms-2 text-sm text-gray-600"},z={class:"flex items-center justify-end mt-4"},J=b({__name:"Login",props:{canResetPassword:{type:Boolean},status:{}},setup(c){const{t:a}=B(),s=C({email:"",password:"",remember:!1}),u=()=>{s.post(route("login"),{onFinish:()=>{s.reset("password")}})};return(n,t)=>(d(),p(e(I),null,{default:m(()=>[o(e($),null,{default:m(()=>[r("title",null,i(e(a)("auth.login.title")),1)]),_:1}),n.status?(d(),v("div",j,i(n.status),1)):f("",!0),r("form",{onSubmit:U(u,["prevent"]),class:"w-full mx-auto sm:max-w-md px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg"},[r("div",null,[o(e(h),{forId:"email",value:e(a)("auth.login.emailLabel")},null,8,["value"]),o(e(_),{id:"email",type:"email",class:"mt-1 block w-full",modelValue:e(s).email,"onUpdate:modelValue":t[0]||(t[0]=l=>e(s).email=l),required:"",autofocus:"",autocomplete:"username"},null,8,["modelValue"]),o(e(w),{class:"mt-2",message:e(s).errors.email},null,8,["message"])]),r("div",D,[o(e(h),{forId:"password",value:e(a)("auth.login.passwordLabel")},null,8,["value"]),o(e(_),{id:"password",type:"password",class:"mt-1 block w-full",modelValue:e(s).password,"onUpdate:modelValue":t[1]||(t[1]=l=>e(s).password=l),required:"",autocomplete:"current-password"},null,8,["modelValue"]),o(e(w),{class:"mt-2",message:e(s).errors.password},null,8,["message"])]),r("div",R,[r("label",S,[o(e(M),{name:"remember",checked:e(s).remember,"onUpdate:checked":t[2]||(t[2]=l=>e(s).remember=l)},null,8,["checked"]),r("span",T,i(e(a)("auth.login.rememberMe")),1)])]),r("div",z,[n.canResetPassword?(d(),p(e(P),{key:0,href:n.route("password.request"),class:"underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"},{default:m(()=>[g(i(e(a)("auth.login.forgotPassword")),1)]),_:1},8,["href"])):f("",!0),o(e(q),{class:N(["ms-4",{"opacity-25":e(s).processing}]),disabled:e(s).processing},{default:m(()=>[g(i(e(a)("auth.login.loginButton")),1)]),_:1},8,["class","disabled"])])],32)]),_:1}))}});export{J as default};
