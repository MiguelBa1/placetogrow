import{d as v,o as p,h as x,B as g,q as C,x as D,y as V,j as E,c as S,a as n,w as c,k as _,a4 as y,b as t,a5 as h,n as $,i as T,ab as I,u as N,m as k,T as L,t as i,e as s,f as w,ag as M,a9 as W}from"./app-Bpvmybdt.js";import{_ as j}from"./_plugin-vue_export-helper-DlAUqK2U.js";import{_ as z,a as F,b as K}from"./InputLabel.vue_vue_type_script_setup_true_lang-CIDNzrCZ.js";const q=["type"],O=v({__name:"SecondaryButton",props:{type:{default:"button"}},setup(d){return(e,l)=>(p(),x("button",{type:e.type,class:"inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150"},[g(e.$slots,"default")],8,q))}}),P={class:"fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50","scroll-region":""},A=t("div",{class:"absolute inset-0 bg-gray-500 opacity-75"},null,-1),G=[A],H=v({__name:"Modal",props:{show:{type:Boolean,default:!1},maxWidth:{default:"2xl"},closeable:{type:Boolean,default:!0}},emits:["close"],setup(d,{emit:e}){const l=d,u=e;C(()=>l.show,()=>{l.show?document.body.style.overflow="hidden":document.body.style.overflow="visible"});const o=()=>{l.closeable&&u("close")},m=a=>{a.key==="Escape"&&l.show&&o()};D(()=>document.addEventListener("keydown",m)),V(()=>{document.removeEventListener("keydown",m),document.body.style.overflow="visible"});const f=E(()=>({sm:"sm:max-w-sm",md:"sm:max-w-md",lg:"sm:max-w-lg",xl:"sm:max-w-xl","2xl":"sm:max-w-2xl"})[l.maxWidth]);return(a,r)=>(p(),S(I,{to:"body"},[n(h,{"leave-active-class":"duration-200"},{default:c(()=>[_(t("div",P,[n(h,{"enter-active-class":"ease-out duration-300","enter-from-class":"opacity-0","enter-to-class":"opacity-100","leave-active-class":"ease-in duration-200","leave-from-class":"opacity-100","leave-to-class":"opacity-0"},{default:c(()=>[_(t("div",{class:"fixed inset-0 transform transition-all",onClick:o},G,512),[[y,a.show]])]),_:1}),n(h,{"enter-active-class":"ease-out duration-300","enter-from-class":"opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95","enter-to-class":"opacity-100 translate-y-0 sm:scale-100","leave-active-class":"ease-in duration-200","leave-from-class":"opacity-100 translate-y-0 sm:scale-100","leave-to-class":"opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"},{default:c(()=>[_(t("div",{class:$(["mb-6 bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full sm:mx-auto",f.value])},[a.show?g(a.$slots,"default",{key:0}):T("",!0)],2),[[y,a.show]])]),_:3})],512),[[y,a.show]])]),_:3})]))}}),J={},Q={class:"inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150"};function R(d,e){return p(),x("button",Q,[g(d.$slots,"default")])}const U=j(J,[["render",R]]),X={class:"space-y-6"},Y={class:"text-lg font-medium text-gray-900"},Z={class:"mt-1 text-sm text-gray-600"},ee={class:"p-6"},se={class:"text-lg font-medium text-gray-900"},te={class:"mt-1 text-sm text-gray-600"},oe={class:"mt-6"},ae={class:"mt-6 flex justify-end"},ie=v({__name:"DeleteUserForm",setup(d){const{t:e}=N(),l=k(!1),u=k(null),o=L({password:""}),m=()=>{l.value=!0,W(()=>{var r;return(r=u.value)==null?void 0:r.focus()})},f=()=>{o.delete(route("profile.edit.destroy"),{preserveScroll:!0,onSuccess:()=>a(),onError:()=>{var r;return(r=u.value)==null?void 0:r.focus()},onFinish:()=>{o.reset()}})},a=()=>{l.value=!1,o.reset()};return(r,b)=>(p(),x("section",X,[t("header",null,[t("h2",Y,i(s(e)("profile.edit.deleteUser.title")),1),t("p",Z,i(s(e)("profile.edit.deleteUser.description")),1)]),n(U,{onClick:m},{default:c(()=>[w(i(s(e)("profile.edit.deleteUser.deleteButton")),1)]),_:1}),n(H,{show:l.value,onClose:a},{default:c(()=>[t("div",ee,[t("h2",se,i(s(e)("profile.edit.deleteUser.confirmDeleteTitle")),1),t("p",te,i(s(e)("profile.edit.deleteUser.confirmDeleteDescription")),1),t("div",oe,[n(z,{forId:"password",value:s(e)("profile.edit.deleteUser.passwordLabel"),class:"sr-only"},null,8,["value"]),n(F,{id:"password",ref_key:"passwordInput",ref:u,modelValue:s(o).password,"onUpdate:modelValue":b[0]||(b[0]=B=>s(o).password=B),type:"password",class:"mt-1 block w-3/4",placeholder:"Password",onKeyup:M(f,["enter"])},null,8,["modelValue"]),n(K,{message:s(o).errors.password,class:"mt-2"},null,8,["message"])]),t("div",ae,[n(O,{onClick:a},{default:c(()=>[w(i(s(e)("profile.edit.deleteUser.cancelButton")),1)]),_:1}),n(U,{class:$(["ms-3",{"opacity-25":s(o).processing}]),disabled:s(o).processing,onClick:f},{default:c(()=>[w(i(s(e)("profile.edit.deleteUser.deleteButton")),1)]),_:1},8,["class","disabled"])])])]),_:1},8,["show"])]))}});export{ie as _};