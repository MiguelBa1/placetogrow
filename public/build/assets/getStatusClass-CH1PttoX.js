import{d as c,u,o as m,c as d,w as n,b,t as o,n as p,e as t,f as _}from"./app-Bpvmybdt.js";import"./ApplicationLogo-B_getK0f.js";import{_ as x}from"./DataTable.vue_vue_type_script_setup_true_lang-Blwdu_ZY.js";import"./PrimaryButton-DxnYWVKS.js";var s=(e=>(e.PENDING="PENDING",e.PAID="PAID",e.EXPIRED="EXPIRED",e))(s||{});const y=c({__name:"InvoicesTable",props:{invoiceList:{}},setup(e){const{t:l}=u(),i=v(l);return(r,D)=>(m(),d(t(x),{columns:t(i),rows:r.invoiceList.data,class:"rounded-lg"},{"cell-status":n(({row:a})=>[b("span",{class:p(t(f)(a.status.value))},o(a.status.label),3)]),"cell-amount":n(({row:a})=>[_(o(`$ ${a.amount}`),1)]),_:1},8,["columns","rows"]))}}),v=e=>[{key:"reference",label:e("invoices.index.table.reference")},{key:"name",label:e("invoices.index.table.name")},{key:"status",label:e("invoices.index.table.status")},{key:"document_number",label:e("invoices.index.table.document_number")},{key:"amount",label:e("invoices.index.table.amount")},{key:"expiration_date",label:e("invoices.index.table.expiration_date")}],f=e=>{switch(e){case s.PAID:return"text-green-600";case s.EXPIRED:return"text-red-600";case s.PENDING:return"text-yellow-600";default:return""}};export{y as _};
