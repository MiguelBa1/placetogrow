import{d as $,m as B,u as C,r as F,U,o as b,h as g,a,w as d,b as r,t as u,e,Z as D,f as h,F as x,D as E,g as N,O}from"./app-BCmDl1Zp.js";import{_ as T}from"./MainLayout.vue_vue_type_script_setup_true_lang-B9ftLfNw.js";import{_ as c}from"./InputField.vue_vue_type_script_setup_true_lang-BEbZPdTR.js";import{_ as j}from"./Listbox.vue_vue_type_script_setup_true_lang-EDYgO1Uf.js";import"./ApplicationLogo-BR7ADdCp.js";import{r as I,_ as L}from"./GlobeAmericasIcon-DK3RHF3a.js";import{_ as q}from"./Button.vue_vue_type_script_setup_true_lang-Y_b8n5pJ.js";import"./PrimaryButton-CcCgdORT.js";import"./keyboard-BLIVt2fl.js";import"./_plugin-vue_export-helper-DlAUqK2U.js";const M={class:"flex justify-between items-center"},Z={class:"font-semibold text-xl text-gray-800 leading-tight"},z={class:"space-x-2"},A={class:"flex items-center gap-1 text-lg font-medium text-gray-900"},G={class:"col-span-2"},le=$({__name:"Edit",props:{microsite:{},timeUnits:{},plan:{}},setup(k){const y=B(),{t:l}=C(),{microsite:w,timeUnits:H,plan:m}=k,p=F(!1),o=U({price:m.price,total_duration:m.total_duration,billing_frequency:m.billing_frequency,time_unit:m.time_unit,translations:m.translations}),i=U({price:"",total_duration:"",billing_frequency:"",time_unit:"",translations:[{name:"",description:""},{name:"",description:""}]}),V=()=>{p.value=!0,O.put(route("microsites.plans.update",{microsite:w,plan:m}),o,{preserveScroll:!0,onSuccess:()=>{y.success(l("plans.edit.success"))},onError:t=>{i.price=t==null?void 0:t.price,i.total_duration=t==null?void 0:t.total_duration,i.billing_frequency=t==null?void 0:t.billing_frequency,i.time_unit=t==null?void 0:t.time_unit,i.translations=[{name:t["translations.0.name"],description:t["translations.0.description"]},{name:t["translations.1.name"],description:t["translations.1.description"]}],y.error(l("plans.edit.error"))},onFinish:()=>{p.value=!1}})},S=()=>{history.back()};return(t,s)=>(b(),g(x,null,[a(e(D),null,{default:d(()=>[r("title",null,`
            `+u(e(l)("plans.edit.title"))+`
        `,1)]),_:1}),a(e(T),null,{header:d(()=>[r("div",M,[r("h2",Z,u(e(l)("plans.edit.header")),1),r("div",z,[a(e(q),{variant:"secondary",color:"gray",onClick:S},{default:d(()=>[h(u(e(l)("common.back")),1)]),_:1})])])]),default:d(()=>[r("form",{onSubmit:N(V,["prevent"]),class:"w-full p-4 sm:p-8 bg-white shadow sm:rounded-lg grid grid-cols-2 gap-4"},[a(e(c),{id:"price",modelValue:o.price,"onUpdate:modelValue":s[0]||(s[0]=n=>o.price=n),label:e(l)("plans.edit.form.price"),type:"number",error:i.price},null,8,["modelValue","label","error"]),a(e(c),{id:"total_duration",modelValue:o.total_duration,"onUpdate:modelValue":s[1]||(s[1]=n=>o.total_duration=n),label:e(l)("plans.edit.form.totalDuration"),type:"number",error:i.total_duration},null,8,["modelValue","label","error"]),a(e(j),{id:"time_unit",modelValue:o.time_unit,"onUpdate:modelValue":s[2]||(s[2]=n=>o.time_unit=n),label:e(l)("plans.edit.form.timeUnit"),options:t.timeUnits,error:i.time_unit},null,8,["modelValue","label","options","error"]),a(e(c),{id:"billing_frequency",modelValue:o.billing_frequency,"onUpdate:modelValue":s[3]||(s[3]=n=>o.billing_frequency=n),label:e(l)("plans.edit.form.billingFrequency"),type:"number",error:i.billing_frequency},null,8,["modelValue","label","error"]),(b(!0),g(x,null,E(o.translations,(n,_)=>{var v;return b(),g("div",{key:_,class:"col-span-2 space-y-4"},[r("h3",A,[a(e(I),{class:"h-5 w-5 inline-block text-blue-500"}),r("span",null,u(e(l)("plans.edit.form.content",{locale:n.locale})),1)]),a(e(c),{id:"name",modelValue:n.name,"onUpdate:modelValue":f=>n.name=f,label:e(l)("plans.edit.form.name"),type:"text",error:i.translations[_].name},null,8,["modelValue","onUpdate:modelValue","label","error"]),a(e(L),{id:"description",modelValue:n.description,"onUpdate:modelValue":f=>n.description=f,label:e(l)("plans.edit.form.description"),error:(v=i.translations[_])==null?void 0:v.description,rows:6},null,8,["modelValue","onUpdate:modelValue","label","error"])])}),128)),r("div",G,[a(e(q),{variant:"primary",onClick:V},{default:d(()=>[h(u(p.value?e(l)("common.loading"):e(l)("common.form.save")),1)]),_:1})])],32)]),_:1})],64))}});export{le as default};