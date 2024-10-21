var R=(i,e,t)=>{if(!e.has(i))throw TypeError("Cannot "+t)};var o=(i,e,t)=>(R(i,e,"read from private field"),t?t.call(i):e.get(i)),b=(i,e,t)=>{if(e.has(i))throw TypeError("Cannot add the same private member more than once");e instanceof WeakSet?e.add(i):e.set(i,t)},S=(i,e,t,s)=>(R(i,e,"write to private field"),s?s.call(i,t):e.set(i,t),t);var x=(i,e,t)=>(R(i,e,"access private method"),t);import{X as T,_ as q,ai as F,aj as A,a7 as z,j as Q,U as X,a9 as G,z as K,ab as H,ac as J,a8 as W,aa as Y,ad as Z,ae as ee,d as te,m as se,u as oe,r as E,o as g,h as C,b as y,t as P,e as h,F as ie,D as ae,c as B,ah as ne,a as re,w as ue,f as ce,g as le,i as me}from"./app-BCmDl1Zp.js";import{_ as N}from"./InputField.vue_vue_type_script_setup_true_lang-BEbZPdTR.js";import{_ as he}from"./Listbox.vue_vue_type_script_setup_true_lang-EDYgO1Uf.js";import"./ApplicationLogo-BR7ADdCp.js";import{_ as de}from"./Button.vue_vue_type_script_setup_true_lang-Y_b8n5pJ.js";import"./PrimaryButton-CcCgdORT.js";import{u as pe}from"./useQueryClient-Wm0ADdMf.js";import"./index-BMSQkwth.js";import{_ as ve}from"./pendingInvoicesTableColumns--dn1ek_9.js";import"./dayjs.min-Cm2y4O4P.js";var v,f,n,d,O,V,I,U,$,fe=($=class extends T{constructor(e,t){super();b(this,O);b(this,I);b(this,v,void 0);b(this,f,void 0);b(this,n,void 0);b(this,d,void 0);S(this,v,e),this.setOptions(t),this.bindMethods(),x(this,O,V).call(this)}bindMethods(){this.mutate=this.mutate.bind(this),this.reset=this.reset.bind(this)}setOptions(e){var s;const t=this.options;this.options=o(this,v).defaultMutationOptions(e),q(this.options,t)||o(this,v).getMutationCache().notify({type:"observerOptionsUpdated",mutation:o(this,n),observer:this}),t!=null&&t.mutationKey&&this.options.mutationKey&&F(t.mutationKey)!==F(this.options.mutationKey)?this.reset():((s=o(this,n))==null?void 0:s.state.status)==="pending"&&o(this,n).setOptions(this.options)}onUnsubscribe(){var e;this.hasListeners()||(e=o(this,n))==null||e.removeObserver(this)}onMutationUpdate(e){x(this,O,V).call(this),x(this,I,U).call(this,e)}getCurrentResult(){return o(this,f)}reset(){var e;(e=o(this,n))==null||e.removeObserver(this),S(this,n,void 0),x(this,O,V).call(this),x(this,I,U).call(this)}mutate(e,t){var s;return S(this,d,t),(s=o(this,n))==null||s.removeObserver(this),S(this,n,o(this,v).getMutationCache().build(o(this,v),this.options)),o(this,n).addObserver(this),o(this,n).execute(e)}},v=new WeakMap,f=new WeakMap,n=new WeakMap,d=new WeakMap,O=new WeakSet,V=function(){var t;const e=((t=o(this,n))==null?void 0:t.state)??A();S(this,f,{...e,isPending:e.status==="pending",isSuccess:e.status==="success",isError:e.status==="error",isIdle:e.status==="idle",mutate:this.mutate,reset:this.reset})},I=new WeakSet,U=function(e){z.batch(()=>{var t,s,u,c,M,m,_,r;if(o(this,d)&&this.hasListeners()){const l=o(this,f).variables,w=o(this,f).context;(e==null?void 0:e.type)==="success"?((s=(t=o(this,d)).onSuccess)==null||s.call(t,e.data,l,w),(c=(u=o(this,d)).onSettled)==null||c.call(u,e.data,null,l,w)):(e==null?void 0:e.type)==="error"&&((m=(M=o(this,d)).onError)==null||m.call(M,e.error,l,w),(r=(_=o(this,d)).onSettled)==null||r.call(_,void 0,e.error,l,w))}this.listeners.forEach(l=>{l(o(this,f))})})},$);function be(i,e){const t=pe(),s=Q(()=>t.defaultMutationOptions(W(i))),u=new fe(t,s.value),c=X(u.getCurrentResult()),M=u.subscribe(r=>{G(c,r)}),m=(r,l)=>{u.mutate(r,l).catch(()=>{})};K(s,()=>{u.setOptions(s.value)}),Z(()=>{M()});const _=H(J(c));return K(()=>c.error,r=>{if(r&&Y(s.value.throwOnError,[r]))throw r}),{..._,mutate:m,mutateAsync:c.mutate,reset:c.reset}}function ge(){return be({mutationFn:async({document_number:i,reference:e,micrositeSlug:t})=>{const{data:s}=await ee.get(route("invoice-payments.pending-invoices",t),{params:{document_number:i,reference:e}});return s}})}const ye={class:"space-y-4 p-10 bg-white rounded-xl shadow-sm"},_e={class:"text-2xl font-bold"},we={class:"text-gray-600"},Se={class:"grid grid-cols-1 md:grid-cols-2 gap-4"},xe={class:"space-y-3"},Oe={key:0,class:"text-center"},Me={key:2},Ce={class:"text-center text-gray-500"},Ne=te({__name:"InvoicePaymentForm",props:{fields:{},microsite:{}},setup(i){const e=se(),{t}=oe(),s=E({reference:"",document_number:""}),u=E({}),{microsite:c,fields:M}=i,m=E(!1),_=p=>{switch(p){case"text":case"email":case"password":case"number":return N;case"select":return he;default:return N}},{mutateAsync:r,isPending:l,isSuccess:w}=ge(),D=E([]),j=async()=>{m.value=!0,await r({document_number:s.value.document_number,reference:s.value.reference,micrositeSlug:c.slug},{onError:p=>{var k,a;e.error(t("common.form.error")),u.value=((a=(k=p.response)==null?void 0:k.data)==null?void 0:a.errors)??{},m.value=!1},onSuccess:({data:p})=>{D.value=p}}),m.value=!1};return(p,k)=>(g(),C("div",ye,[y("h1",_e,P(h(t)("invoicePayments.show.form.title")),1),y("p",we,P(h(t)("invoicePayments.show.form.instructions")),1),y("form",{onSubmit:le(j,["prevent"]),class:"space-y-4"},[y("div",Se,[(g(!0),C(ie,null,ae(p.fields,a=>(g(),C("div",{key:a.id},[(g(),B(ne(_(a.type)),{id:a.name,type:a.type,name:a.name,label:a.label,modelValue:s.value[a.name],"onUpdate:modelValue":L=>s.value[a.name]=L,options:a.options,error:u.value[a.name]?u.value[a.name][0]:void 0},null,8,["id","type","name","label","modelValue","onUpdate:modelValue","options","error"]))]))),128))]),y("div",xe,[re(h(de),{type:"submit",disabled:m.value,color:"green"},{default:ue(()=>[ce(P(h(t)("invoicePayments.show.form.search")),1)]),_:1},8,["disabled"])])],32),y("div",null,[h(l)?(g(),C("div",Oe,P(h(t)("common.loading")),1)):D.value.length>0?(g(),B(h(ve),{key:1,pendingInvoices:D.value,micrositeSlug:p.microsite.slug,formData:s.value},null,8,["pendingInvoices","micrositeSlug","formData"])):h(w)&&D.value.length===0?(g(),C("div",Me,[y("p",Ce,P(h(t)("invoicePayments.show.form.noResults")),1)])):me("",!0)])]))}});export{Ne as _};
