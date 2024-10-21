import{d as g,u as v,r as p,o as w,h as y,a as e,w as l,b as t,t as r,e as a,Z as x,g as D,f as V,F as $,O as F}from"./app-BCmDl1Zp.js";import{_ as T}from"./MainLayout.vue_vue_type_script_setup_true_lang-B9ftLfNw.js";import{_ as B}from"./ApprovedTransactionsByMicrositeTypeChart.vue_vue_type_script_setup_true_lang-BrhA9PeH.js";import{_ as S}from"./InvoiceDistributionChart.vue_vue_type_script_setup_true_lang-DdsMrCoj.js";import{_ as U}from"./PaymentsOverTimeChart.vue_vue_type_script_setup_true_lang-j91FrwFI.js";import{_ as I}from"./SubscriptionDistributionChart.vue_vue_type_script_setup_true_lang-BOHxS0Qp.js";import{_ as O}from"./TopMicrositesChart.vue_vue_type_script_setup_true_lang-DBLw36Uq.js";import{_ as u}from"./InputField.vue_vue_type_script_setup_true_lang-BEbZPdTR.js";import"./ApplicationLogo-BR7ADdCp.js";import{_ as M}from"./Button.vue_vue_type_script_setup_true_lang-Y_b8n5pJ.js";import"./PrimaryButton-CcCgdORT.js";import"./index-B4YHFS-G.js";import"./dayjs.min-Cm2y4O4P.js";import"./_plugin-vue_export-helper-DlAUqK2U.js";const N={class:"font-semibold text-xl text-gray-800 leading-tight"},k={class:"bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"},C={class:"flex justify-between items-center col-span-3"},E={class:"mb-4 text-gray-600"},j={class:"mb-4 text-gray-500 text-sm"},Z={class:"md:col-span-2 bg-white shadow rounded p-4"},q={class:"bg-white shadow rounded p-4"},z={class:"bg-white shadow rounded p-4"},A={class:"bg-white shadow rounded p-4"},G={class:"bg-white shadow rounded p-4"},ia=g({__name:"Index",props:{data:{},lastUpdated:{},startDate:{},endDate:{}},setup(c){const{data:H,lastUpdated:J,startDate:_,endDate:f}=c,{t:d}=v(),h=new Date().toISOString().split("T")[0],o=p(_),i=p(f),b=()=>{o.value&&i.value&&F.visit("/dashboard",{method:"get",data:{start_date:o.value,end_date:i.value}})};return(s,n)=>(w(),y($,null,[e(a(x),null,{default:l(()=>[t("title",null,r(a(d)("dashboard.index.title")),1)]),_:1}),e(a(T),null,{header:l(()=>[t("h2",N,r(a(d)("dashboard.index.title")),1)]),default:l(()=>[t("div",k,[t("div",C,[t("div",null,[t("p",E,r(a(d)("dashboard.index.general_information")),1),t("p",j,r(a(d)("dashboard.index.last_updated",{date:s.lastUpdated})),1)]),t("form",{class:"flex gap-4 items-end mb-4",onSubmit:D(b,["prevent"])},[e(a(u),{id:"start_date",type:"date",label:"Fecha de Inicio",modelValue:o.value,"onUpdate:modelValue":n[0]||(n[0]=m=>o.value=m)},null,8,["modelValue"]),e(a(u),{id:"end_date",type:"date",label:"Fecha de Fin",max:a(h),modelValue:i.value,"onUpdate:modelValue":n[1]||(n[1]=m=>i.value=m)},null,8,["max","modelValue"]),e(a(M),{type:"submit",variant:"secondary",color:"blue"},{default:l(()=>[V(" Filtrar ")]),_:1})],32)]),t("div",Z,[e(a(U),{data:s.data.paymentsOverTime},null,8,["data"])]),t("div",q,[e(a(S),{data:s.data.invoiceDistribution},null,8,["data"])]),t("div",z,[e(a(O),{data:s.data.topMicrositesByTransactions},null,8,["data"])]),t("div",A,[e(a(I),{data:s.data.subscriptionDistribution},null,8,["data"])]),t("div",G,[e(a(B),{data:s.data.approvedTransactionsByMicrositeType},null,8,["data"])])])]),_:1})],64))}});export{ia as default};