import{d as _,u as x,m as f,T as y,o as g,h as w,a as n,w as l,b as o,t as i,e,Z as h,f as V,g as v,F as S}from"./app-BCmDl1Zp.js";import{_ as k}from"./MainLayout.vue_vue_type_script_setup_true_lang-B9ftLfNw.js";import{_ as p}from"./InputField.vue_vue_type_script_setup_true_lang-BEbZPdTR.js";import"./ApplicationLogo-BR7ADdCp.js";import{_ as B}from"./Button.vue_vue_type_script_setup_true_lang-Y_b8n5pJ.js";import"./PrimaryButton-CcCgdORT.js";import"./_plugin-vue_export-helper-DlAUqK2U.js";const N={class:"space-y-2"},E={class:"font-semibold text-xl text-gray-800"},L={class:"text-sm text-gray-600"},T={class:"w-full"},$={class:"text-sm text-gray-600"},j=_({__name:"Index",setup(q){const{t:s}=x(),u=f(),t=y({document_number:"",email:""}),b=()=>{t.post(route("subscriptions.send-link"),{preserveScroll:!0,onSuccess:()=>{u.success(s("subscriptions.index.linkSent")),t.reset()},onError:d=>{const r=d.invalid??s("subscriptions.index.linkError");u.error(r)}})};return(d,r)=>(g(),w(S,null,[n(e(h),null,{default:l(()=>[o("title",null,`
            `+i(e(s)("subscriptions.index.title"))+`
        `,1)]),_:1}),n(e(k),null,{default:l(()=>{var m,c;return[o("form",{onSubmit:v(b,["prevent"]),class:"space-y-4 w-full mx-auto sm:max-w-md px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg"},[o("div",N,[o("h1",E,i(e(s)("subscriptions.index.accessSubscriptions")),1),o("p",L,i(e(s)("subscriptions.index.enterDetails")),1)]),n(e(p),{id:"document_number",type:"text",label:e(s)("subscriptions.index.documentNumberLabel"),modelValue:e(t).document_number,"onUpdate:modelValue":r[0]||(r[0]=a=>e(t).document_number=a),required:"",errors:(m=e(t).errors)==null?void 0:m.document_number},null,8,["label","modelValue","errors"]),n(e(p),{id:"email",type:"email",label:e(s)("subscriptions.index.emailLabel"),modelValue:e(t).email,"onUpdate:modelValue":r[1]||(r[1]=a=>e(t).email=a),required:"",errors:(c=e(t).errors)==null?void 0:c.email},null,8,["label","modelValue","errors"]),o("div",T,[n(e(B),{type:"submit",variant:"primary",color:"blue",class:"w-full",disabled:e(t).processing},{default:l(()=>[V(i(e(s)("subscriptions.index.sendLinkButton")),1)]),_:1},8,["disabled"])]),o("p",$,i(e(s)("subscriptions.index.afterSend")),1)],32)]}),_:1})],64))}});export{j as default};
