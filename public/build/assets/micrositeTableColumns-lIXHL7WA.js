import{o,h as n,b as m,d as S,Q as B,u as O,r as h,a as r,w as a,f as v,t as y,e,c as d,l as p,i as u,F as k}from"./app-BIJ4Abzx.js";import"./ApplicationLogo-DROgA_Il.js";import{_ as V}from"./DataTable.vue_vue_type_script_setup_true_lang-CUycw6XG.js";import{_ as j}from"./Pagination.vue_vue_type_script_setup_true_lang-ClEA1f1L.js";import"./PrimaryButton-D0pUaKYk.js";import{_ as H}from"./DeleteMicrositeModal.vue_vue_type_script_setup_true_lang-Dy6YKEMA.js";import{_ as D}from"./RestoreMicrositeModal.vue_vue_type_script_setup_true_lang-tAEU_lK-.js";import"./dayjs.min-Dm1FWret.js";import{r as N}from"./EyeIcon-BDiPH9dM.js";import{r as A}from"./PencilSquareIcon-CCWOJicL.js";import{r as R}from"./TrashIcon-dHQxvpEe.js";import{r as T}from"./ArrowUturnLeftIcon-DhtuEno2.js";function Z(i,l){return o(),n("svg",{xmlns:"http://www.w3.org/2000/svg",fill:"none",viewBox:"0 0 24 24","stroke-width":"1.5",stroke:"currentColor","aria-hidden":"true","data-slot":"icon"},[m("path",{"stroke-linecap":"round","stroke-linejoin":"round",d:"M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"})])}function F(i,l){return o(),n("svg",{xmlns:"http://www.w3.org/2000/svg",fill:"none",viewBox:"0 0 24 24","stroke-width":"1.5",stroke:"currentColor","aria-hidden":"true","data-slot":"icon"},[m("path",{"stroke-linecap":"round","stroke-linejoin":"round",d:"M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z"}),m("path",{"stroke-linecap":"round","stroke-linejoin":"round",d:"M6 6h.008v.008H6V6Z"})])}const I={class:"w-full space-y-4"},E={class:"flex justify-center"},L={key:0,class:"flex justify-center gap-2"},Q=["onClick"],q=["onClick"],re=S({__name:"MicrositesTable",props:{microsites:{}},setup(i){const{props:{auth:{permissions:l}}}=B(),{t:g}=O(),b=z(g),c=h(null),_=h(!1),f=h(!1),x=t=>{c.value=t,_.value=!0},w=()=>{_.value=!1,c.value=null},M=t=>{c.value=t,f.value=!0},C=()=>{f.value=!1,c.value=null};return(t,G)=>(o(),n(k,null,[m("div",I,[r(e(V),{columns:e(b),rows:t.microsites.data,class:"rounded-lg"},{"cell-type":a(({row:s})=>[v(y(s.type.label),1)]),"cell-category":a(({row:s})=>[v(y(s.category.name),1)]),"cell-actions":a(({row:s})=>[m("div",E,[s.deleted_at?(o(),n(k,{key:1},[e(l).includes("restore_microsite")?(o(),n("button",{key:0,class:"text-green-600 hover:text-green-800",onClick:$=>M(s.slug)},[r(e(T),{class:"w-5 h-5"})],8,q)):u("",!0)],64)):(o(),n("div",L,[e(l).includes("view_microsite")?(o(),d(e(p),{key:0,href:t.route("microsites.show",{microsite:s.slug,page:t.microsites.meta.current_page}),class:"text-gray-600 hover:text-gray-900"},{default:a(()=>[r(e(N),{class:"w-5 h-5"})]),_:2},1032,["href"])):u("",!0),e(l).includes("update_microsite")?(o(),d(e(p),{key:1,href:t.route("microsites.edit",{microsite:s.slug,page:t.microsites.meta.current_page}),class:"text-blue-600 hover:text-blue-900"},{default:a(()=>[r(e(A),{class:"w-5 h-5"})]),_:2},1032,["href"])):u("",!0),s.type.value==="invoice"&&e(l).includes("view_any_invoice")?(o(),d(e(p),{key:2,href:t.route("microsites.invoices.index",{microsite:s.slug}),title:"Invoices"},{default:a(()=>[r(e(Z),{class:"w-5 h-5"})]),_:2},1032,["href"])):u("",!0),s.type.value==="subscription"?(o(),d(e(p),{key:3,href:t.route("microsites.subscriptions.index",{microsite:s.slug}),title:"Subscriptions"},{default:a(()=>[r(e(F),{class:"w-5 h-5"})]),_:2},1032,["href"])):u("",!0),e(l).includes("delete_microsite")?(o(),n("button",{key:4,class:"text-red-600 hover:text-red-900",onClick:$=>x(s.slug)},[r(e(R),{class:"w-5 h-5"})],8,Q)):u("",!0)]))])]),_:1},8,["columns","rows"]),r(e(j),{links:t.microsites.meta.links},null,8,["links"])]),r(e(H),{isOpen:_.value,micrositeSlug:c.value,onCloseModal:w},null,8,["isOpen","micrositeSlug"]),r(e(D),{isOpen:f.value,micrositeSlug:c.value,onCloseModal:C},null,8,["isOpen","micrositeSlug"])],64))}}),z=i=>[{key:"id",label:i("microsites.index.table.id")},{key:"name",label:i("microsites.index.table.name")},{key:"category",label:i("microsites.index.table.category")},{key:"type",label:i("microsites.index.table.type")},{key:"responsible_name",label:i("microsites.index.table.responsible_name")},{key:"payment_currency",label:i("microsites.index.table.payment_currency")},{key:"payment_expiration",label:i("microsites.index.table.payment_expiration")},{key:"actions",label:i("microsites.index.table.actions")}];export{re as _};
