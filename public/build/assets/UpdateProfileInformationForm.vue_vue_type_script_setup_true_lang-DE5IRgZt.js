import{d as y,u as x,Q as h,T as I,o as u,h as d,b as o,t as s,e,a as i,f,w as p,l as V,k as P,a4 as b,i as c,a5 as k,g as w}from"./app-Bpvmybdt.js";import{_ as v,a as _,b as g}from"./InputLabel.vue_vue_type_script_setup_true_lang-CIDNzrCZ.js";import{P as B}from"./PrimaryButton-DxnYWVKS.js";const S={class:"text-lg font-medium text-gray-900"},E={class:"mt-1 text-sm text-gray-600"},N={key:0},$={class:"text-sm mt-2 text-gray-800"},C={class:"flex items-center gap-4"},L={key:0,class:"text-sm text-gray-600"},M=y({__name:"UpdateProfileInformationForm",props:{mustVerifyEmail:{type:Boolean},status:{}},setup(T){const{t}=x(),m=h().props.auth.user,a=I({name:m.name,email:m.email});return(n,l)=>(u(),d("section",null,[o("header",null,[o("h2",S,s(e(t)("profile.edit.updateProfileInformation.title")),1),o("p",E,s(e(t)("profile.edit.updateProfileInformation.description")),1)]),o("form",{onSubmit:l[2]||(l[2]=w(r=>e(a).patch(n.route("profile.edit.update")),["prevent"])),class:"mt-6 space-y-6"},[o("div",null,[i(v,{forId:"name",value:e(t)("profile.edit.updateProfileInformation.nameLabel")},null,8,["value"]),i(_,{id:"name",type:"text",class:"mt-1 block w-full",modelValue:e(a).name,"onUpdate:modelValue":l[0]||(l[0]=r=>e(a).name=r),required:"",autofocus:"",autocomplete:"on"},null,8,["modelValue"]),i(g,{class:"mt-2",message:e(a).errors.name},null,8,["message"])]),o("div",null,[i(v,{forId:"email",value:e(t)("profile.edit.updateProfileInformation.emailLabel")},null,8,["value"]),i(_,{id:"email",type:"email",class:"mt-1 block w-full",modelValue:e(a).email,"onUpdate:modelValue":l[1]||(l[1]=r=>e(a).email=r),required:"",autocomplete:"username"},null,8,["modelValue"]),i(g,{class:"mt-2",message:e(a).errors.email},null,8,["message"])]),n.mustVerifyEmail&&e(m).email_verified_at===null?(u(),d("div",N,[o("p",$,[f(s(e(t)("profile.edit.updateProfileInformation.unverifiedEmail"))+" ",1),i(e(V),{href:n.route("verification.send"),method:"post",as:"button",class:"underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"},{default:p(()=>[f(s(e(t)("profile.edit.updateProfileInformation.resendVerification")),1)]),_:1},8,["href"])]),P(o("div",{class:"mt-2 font-medium text-sm text-green-600"},s(e(t)("profile.edit.updateProfileInformation.verificationLinkSent")),513),[[b,n.status==="verification-link-sent"]])])):c("",!0),o("div",C,[i(B,{disabled:e(a).processing},{default:p(()=>[f(s(e(t)("profile.edit.updateProfileInformation.saveButton")),1)]),_:1},8,["disabled"]),i(k,{"enter-active-class":"transition ease-in-out","enter-from-class":"opacity-0","leave-active-class":"transition ease-in-out","leave-to-class":"opacity-0"},{default:p(()=>[e(a).recentlySuccessful?(u(),d("p",L,s(e(t)("profile.edit.updateProfileInformation.savedMessage")),1)):c("",!0)]),_:1})])],32)]))}});export{M as _};
