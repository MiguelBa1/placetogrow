import{d as S,u as k,p as q,T as C,m as T,o as _,c as y,w as d,a as r,f as V,t as M,e,b as U,i as $,g as B}from"./app-Bpvmybdt.js";import{_ as n}from"./InputField.vue_vue_type_script_setup_true_lang-CfQ2MUfz.js";import{_ as O}from"./Listbox.vue_vue_type_script_setup_true_lang-DSl_v-Rk.js";import"./ApplicationLogo-B_getK0f.js";import{_ as b}from"./Button.vue_vue_type_script_setup_true_lang-3Poo1hdF.js";import{_ as L}from"./Modal.vue_vue_type_script_setup_true_lang-CggkL3MU.js";import"./PrimaryButton-DxnYWVKS.js";import"./dayjs.min-DfHwKj4N.js";import{u as N}from"./useFIeldTypesQuery-Dku_4Koo.js";const A=S({__name:"EditFieldModal",props:{isOpen:{type:Boolean},micrositeSlug:{},field:{}},emits:["closeModal"],setup(v,{emit:w}){const{t:i}=k(),u=q(),{isOpen:g,micrositeSlug:c,field:o}=v,m=w,{data:x,isLoading:f}=N({enabled:g}),s=C({name:o==null?void 0:o.name,type:o==null?void 0:o.type,validation_rules:o==null?void 0:o.validation_rules,translation_es:o==null?void 0:o.translation_es,translation_en:o==null?void 0:o.translation_en,options:(o==null?void 0:o.options)??""}),p=T(null),h=()=>{p.value&&p.value.requestSubmit()},F=()=>{if(!c){console.log("Microsite slug is required");return}s.transform(a=>{var t;return{...a,options:a.type==="select"?a.options.split(",").map(l=>l.trim()):null,validation_rules:(t=a.validation_rules)==null?void 0:t.split(",").map(l=>l.trim()).join("|")}}).put(route("microsites.fields.update",[c,o==null?void 0:o.id]),{onSuccess:()=>{u.success(i("microsites.show.fields.editModal.success")),m("closeModal"),s.reset()},onError:()=>{u.error(i("microsites.show.fields.editModal.error"))},preserveScroll:!0})};return(a,t)=>(_(),y(e(L),{title:e(i)("microsites.show.fields.editModal.title"),isOpen:a.isOpen,onClose:t[7]||(t[7]=l=>m("closeModal"))},{footerButtons:d(()=>[r(e(b),{variant:"secondary",onClick:t[6]||(t[6]=l=>m("closeModal"))},{default:d(()=>[V(M(e(i)("microsites.show.fields.editModal.cancel")),1)]),_:1}),r(e(b),{color:"green",type:"submit",onClick:h},{default:d(()=>[V(M(e(i)("microsites.show.fields.editModal.save")),1)]),_:1})]),default:d(()=>[U("form",{ref_key:"editFormRef",ref:p,onSubmit:B(F,["prevent"]),class:"space-y-2"},[r(e(n),{id:"field-name",type:"text",required:"",modelValue:e(s).name,"onUpdate:modelValue":t[0]||(t[0]=l=>e(s).name=l),label:e(i)("microsites.show.fields.editModal.name"),error:e(s).errors.name},null,8,["modelValue","label","error"]),r(e(O),{id:"field-type",modelValue:e(s).type,"onUpdate:modelValue":t[1]||(t[1]=l=>e(s).type=l),label:e(i)("microsites.show.fields.editModal.type"),error:e(s).errors.type,options:e(x)??[],isLoading:e(f),placeholder:e(f)?e(i)("common.loading"):e(i)("common.select")},null,8,["modelValue","label","error","options","isLoading","placeholder"]),e(s).type==="select"?(_(),y(e(n),{key:0,id:"field-options",type:"text",modelValue:e(s).options,"onUpdate:modelValue":t[2]||(t[2]=l=>e(s).options=l),required:"",label:e(i)("microsites.show.fields.editModal.options"),placeholder:e(i)("microsites.show.fields.editModal.optionsPlaceholder"),error:e(s).errors.options},null,8,["modelValue","label","placeholder","error"])):$("",!0),r(e(n),{id:"field-validation_rules",type:"text",modelValue:e(s).validation_rules,"onUpdate:modelValue":t[3]||(t[3]=l=>e(s).validation_rules=l),label:e(i)("microsites.show.fields.editModal.validationRules"),error:e(s).errors.validation_rules,placeholder:e(i)("microsites.show.fields.validationRulesHelp")},null,8,["modelValue","label","error","placeholder"]),r(e(n),{id:"field-translation_es",type:"text",required:"",modelValue:e(s).translation_es,"onUpdate:modelValue":t[4]||(t[4]=l=>e(s).translation_es=l),label:e(i)("microsites.show.fields.editModal.translations.es"),error:e(s).errors.translation_es},null,8,["modelValue","label","error"]),r(e(n),{id:"field-translation_en",type:"text",required:"",modelValue:e(s).translation_en,"onUpdate:modelValue":t[5]||(t[5]=l=>e(s).translation_en=l),label:e(i)("microsites.show.fields.editModal.translations.en"),error:e(s).errors.translation_en},null,8,["modelValue","label","error"])],544)]),_:1},8,["title","isOpen"]))}});export{A as _};
