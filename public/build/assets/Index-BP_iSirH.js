import{d,u as _,o as n,h as m,a as r,w as e,e as s,Z as f,b as i,t as a,O as b,f as l,c as h,F as y}from"./app-BIJ4Abzx.js";import{_ as g}from"./MainLayout.vue_vue_type_script_setup_true_lang-C15JnSKh.js";import{_ as k}from"./subscriptionTableColumns-NY-nIyJg.js";import"./ApplicationLogo-DROgA_Il.js";import{_ as p}from"./Button.vue_vue_type_script_setup_true_lang-DYVpFlCb.js";import"./PrimaryButton-D0pUaKYk.js";import"./DataTable.vue_vue_type_script_setup_true_lang-CUycw6XG.js";import"./PencilSquareIcon-CCWOJicL.js";import"./TrashIcon-dHQxvpEe.js";import"./ArrowUturnLeftIcon-DhtuEno2.js";import"./_plugin-vue_export-helper-DlAUqK2U.js";const x=i("title",null,"Subscriptions",-1),v={class:"flex justify-between items-center"},B={class:"font-semibold text-xl text-gray-800 leading-tight"},C={class:"space-x-2"},$={key:1,class:"flex items-center justify-center h-96"},w={class:"text-gray-500 text-lg"},A=d({__name:"Index",props:{microsite:{},subscriptions:{}},setup(N){const{t:o}=_(),u=()=>{history.back()};return(t,c)=>(n(),m(y,null,[r(s(f),null,{default:e(()=>[x]),_:1}),r(s(g),null,{header:e(()=>[i("div",v,[i("h2",B,a(s(o)("subscriptions.index.title",{microsite:t.microsite.name})),1),i("div",C,[r(s(p),{variant:"primary",onClick:c[0]||(c[0]=V=>s(b).visit(t.route("microsites.subscriptions.create",{microsite:t.microsite})))},{default:e(()=>[l(a(s(o)("subscriptions.index.create")),1)]),_:1}),r(s(p),{variant:"secondary",color:"gray",onClick:u},{default:e(()=>[l(a(s(o)("common.back")),1)]),_:1})])])]),default:e(()=>[t.subscriptions.data.length>0?(n(),h(s(k),{key:0,subscriptions:t.subscriptions,microsite:t.microsite},null,8,["subscriptions","microsite"])):(n(),m("div",$,[i("p",w,a(s(o)("subscriptions.index.table.no_subscriptions")),1)]))]),_:1})],64))}});export{A as default};