import{d as x,u as h,m as k,o as n,c as y,w as l,b as u,h as b,a as c,e as t,l as v,O as p}from"./app-BIJ4Abzx.js";import"./ApplicationLogo-DROgA_Il.js";import{_ as S}from"./DataTable.vue_vue_type_script_setup_true_lang-CUycw6XG.js";import"./PrimaryButton-D0pUaKYk.js";import{r as C}from"./PencilSquareIcon-CCWOJicL.js";import{r as g}from"./TrashIcon-dHQxvpEe.js";import{r as $}from"./ArrowUturnLeftIcon-DhtuEno2.js";const w={class:"flex justify-center"},B={key:0,class:"flex justify-center gap-2"},E=["onClick"],T=["onClick"],G=x({__name:"SubscriptionsTable",props:{microsite:{},subscriptions:{}},setup(e){const{t:r}=h(),o=k(),{microsite:a,subscriptions:q}=e,d=j(r),m=s=>{p.delete(route("microsites.subscriptions.destroy",{microsite:a,subscription:s}),{preserveScroll:!0,preserveState:!0,onSuccess:()=>{o.success(r("subscriptions.index.delete.success"))},onError:()=>{o.error(r("subscriptions.index.delete.error"))}})},_=s=>{p.put(route("microsites.subscriptions.restore",{microsite:a,subscription:s}),{},{preserveScroll:!0,preserveState:!0,onSuccess:()=>{o.success(r("subscriptions.index.restore.success"))},onError:()=>{o.error(r("subscriptions.index.restore.error"))}})};return(s,N)=>(n(),y(t(S),{rows:s.subscriptions.data,columns:t(d)},{"cell-actions":l(({row:i})=>[u("div",w,[i.deleted_at?(n(),b("button",{key:1,class:"text-green-600 hover:text-green-800",onClick:f=>_(i.id)},[c(t($),{class:"w-5 h-5"})],8,T)):(n(),b("div",B,[c(t(v),{href:s.route("microsites.subscriptions.edit",{microsite:s.microsite,subscription:i.id}),class:"text-blue-600 hover:text-blue-800"},{default:l(()=>[c(t(C),{class:"w-5 h-5"})]),_:2},1032,["href"]),u("button",{class:"text-red-600 hover:text-red-800",onClick:f=>m(i.id)},[c(t(g),{class:"w-5 h-5"})],8,E)]))])]),_:1},8,["rows","columns"]))}}),j=e=>[{key:"name",label:e("subscriptions.index.table.name")},{key:"price",label:e("subscriptions.index.table.price")},{key:"total_duration",label:e("subscriptions.index.table.total_duration")},{key:"billing_frequency",label:e("subscriptions.index.table.billing_frequency")},{key:"created_at",label:e("subscriptions.index.table.created_at")},{key:"actions",label:e("subscriptions.index.table.actions")}];export{G as _};
