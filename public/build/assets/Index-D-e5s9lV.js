import{d as p,u as l,o as a,h as o,a as e,w as n,b as i,t as m,e as t,Z as c,c as u,F as _}from"./app-BCmDl1Zp.js";import{_ as f}from"./MainLayout.vue_vue_type_script_setup_true_lang-B9ftLfNw.js";import{_ as d}from"./transactionTableColumns-BT39LQF3.js";import{_ as y}from"./IndexHeader.vue_vue_type_script_setup_true_lang-BteNeDu0.js";import"./ApplicationLogo-BR7ADdCp.js";import"./_plugin-vue_export-helper-DlAUqK2U.js";import"./PrimaryButton-CcCgdORT.js";import"./DataTable.vue_vue_type_script_setup_true_lang-Zkw0Oib1.js";import"./Pagination.vue_vue_type_script_setup_true_lang-DyCWtOf1.js";import"./getStatusClass-B2_jvcaC.js";import"./PaymentStatus-BztxG7Vj.js";import"./EyeIcon-B3VzzEZO.js";import"./InputField.vue_vue_type_script_setup_true_lang-BEbZPdTR.js";import"./Listbox.vue_vue_type_script_setup_true_lang-EDYgO1Uf.js";import"./keyboard-BLIVt2fl.js";import"./Button.vue_vue_type_script_setup_true_lang-Y_b8n5pJ.js";import"./MagnifyingGlassIcon-l3b5BJEY.js";import"./XMarkIcon-b-Iyq59n.js";const h={key:1,class:"flex justify-center items-center h-96"},k={class:"text-gray-500"},G=p({__name:"Index",props:{transactions:{},filters:{},paymentStatuses:{}},setup(S){const{t:r}=l();return(s,g)=>(a(),o(_,null,[e(t(c),null,{default:n(()=>[i("title",null,`
            `+m(t(r)("transactions.index.title"))+`
        `,1)]),_:1}),e(t(f),null,{header:n(()=>[e(t(y),{filters:s.filters,paymentStatuses:s.paymentStatuses},null,8,["filters","paymentStatuses"])]),default:n(()=>[s.transactions.data.length>0?(a(),u(t(d),{key:0,transactions:s.transactions},null,8,["transactions"])):(a(),o("div",h,[i("p",k,m(t(r)("transactions.index.table.no_transactions")),1)]))]),_:1})],64))}});export{G as default};
