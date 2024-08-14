import{d as h,u as x,o as r,c as g,w as c,a as l,b as t,t as s,e,Z as b,n as P,h as i,i as u,l as E,f as N}from"./app-Bpvmybdt.js";import{_ as v}from"./MainLayout.vue_vue_type_script_setup_true_lang-C7yXbFLh.js";import"./ApplicationLogo-B_getK0f.js";import{_ as D}from"./Button.vue_vue_type_script_setup_true_lang-3Poo1hdF.js";import"./PrimaryButton-DxnYWVKS.js";import{P as n}from"./PaymentStatus-BztxG7Vj.js";import"./_plugin-vue_export-helper-DlAUqK2U.js";const w=(m,a)=>{switch(a){case n.PENDING:return m("payments.result.status.pending");case n.APPROVED:return m("payments.result.status.approved");case n.REJECTED:return m("payments.result.status.rejected");default:return m("payments.result.status.unknown")}},V={class:"max-w-xl bg-white w-full m-auto rounded-xl p-10 shadow-md space-y-8"},k={class:"space-y-6"},R={class:"flex gap-3 flex-col justify-between items-center p-5 border-b-2"},A={class:"text-center"},C={class:"text-xl font-semibold"},S={class:"text-2xl font-bold"},O={class:"text-lg"},B={class:"grid grid-cols-2 gap-y-10 text-center"},I={class:"space-y-2"},j={class:"text-md font-medium text-gray-500"},z={class:"text-sm text-gray-900 font-semibold"},T={class:"space-y-2"},G={class:"text-md font-medium text-gray-500"},J={class:"text-sm text-gray-900 font-semibold"},L={key:0,class:"space-y-2"},$={class:"text-md font-medium text-gray-500"},F={class:"text-sm text-gray-900 font-semibold"},Z={key:1,class:"space-y-2"},q={class:"text-md font-medium text-gray-500"},H={class:"text-sm text-gray-900 font-semibold"},K={class:"space-y-2"},M={class:"text-md font-medium text-gray-500"},Q={class:"text-sm text-gray-900 font-semibold"},U={key:2,class:"space-y-2"},W={class:"text-md font-medium text-gray-500"},X={class:"text-sm text-gray-900 font-semibold"},Y={class:"flex justify-center"},rt=h({__name:"Return",props:{payment:{},customerName:{},micrositeName:{}},setup(m){const{t:a}=x(),{payment:d}=m,p=new Date(d.payment_date).toLocaleString(),y=new Date(d.created_at).toLocaleString(),_=new Intl.NumberFormat().format(d.amount),f=w(a,d.status);return(o,tt)=>(r(),g(e(v),null,{default:c(()=>[l(e(b),null,{default:c(()=>[t("title",null,`
                `+s(e(a)("payments.result.title"))+`
            `,1)]),_:1}),t("div",V,[t("div",k,[t("div",R,[t("div",A,[t("p",C,s(e(a)("payments.result.greeting")),1),t("p",S,s(o.customerName),1),t("p",O,s(e(a)("payments.result.payment_status",{micrositeName:o.micrositeName})),1)]),t("div",{class:P([{"bg-red-200":o.payment.status===e(n).REJECTED,"bg-orange-200":o.payment.status===e(n).PENDING,"bg-green-200":o.payment.status===e(n).APPROVED},"text-xl font-bold text-gray-700 px-7 py-2 rounded-2xl"])},s(e(f)),3)]),t("dl",B,[t("div",I,[t("dt",j,s(e(a)("payments.result.payment.reference")),1),t("dd",z,s(o.payment.reference),1)]),t("div",T,[t("dt",G,s(e(a)("payments.result.payment.total")),1),t("dd",J,s(e(_))+" "+s(o.payment.currency),1)]),o.payment.status===e(n).APPROVED?(r(),i("div",L,[t("dt",$,s(e(a)("payments.result.payment.method")),1),t("dd",F,s(o.payment.payment_method_name),1)])):u("",!0),o.payment.status===e(n).APPROVED?(r(),i("div",Z,[t("dt",q,s(e(a)("payments.result.payment.authorization_code")),1),t("dd",H,s(o.payment.authorization),1)])):u("",!0),t("div",K,[t("dt",M,s(e(a)("payments.result.payment.created_at")),1),t("dd",Q,s(e(y)),1)]),o.payment.status===e(n).APPROVED?(r(),i("div",U,[t("dt",W,s(e(a)("payments.result.payment.date")),1),t("dd",X,s(e(p)),1)])):u("",!0)])]),t("div",Y,[l(e(D),null,{default:c(()=>[l(e(E),{href:o.route("home")},{default:c(()=>[N(s(e(a)("payments.result.make_another_payment")),1)]),_:1},8,["href"])]),_:1})])])]),_:1}))}});export{rt as default};
