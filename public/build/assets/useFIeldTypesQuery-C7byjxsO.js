var Z=(i,t,s)=>{if(!t.has(i))throw TypeError("Cannot "+s)};var e=(i,t,s)=>(Z(i,t,"read from private field"),s?s.call(i):t.get(i)),u=(i,t,s)=>{if(t.has(i))throw TypeError("Cannot add the same private member more than once");t instanceof WeakSet?t.add(i):t.set(i,s)},c=(i,t,s,r)=>(Z(i,t,"write to private field"),r?r.call(i,s):t.set(i,s),s);var f=(i,t,s)=>(Z(i,t,"access private method"),s);import{V as St,W as O,X as tt,Y,_ as wt,$ as ft,a0 as pt,a1 as Qt,a2 as Et,a3 as Ft,a4 as bt,a5 as It,a6 as Ut,J as Dt,a7 as Tt,j as Lt,a8 as xt,L as Mt,q as X,a9 as yt,aa as vt,ab as Pt,ac as jt,ad as At,ae as kt}from"./app-BIJ4Abzx.js";var y,n,k,b,w,L,R,V,x,M,Q,E,S,P,F,A,W,et,_,st,z,it,B,rt,K,nt,N,at,J,ot,$,Ot,Rt,Vt=(Rt=class extends St{constructor(t,s){super();u(this,F);u(this,W);u(this,_);u(this,z);u(this,B);u(this,K);u(this,N);u(this,J);u(this,$);u(this,y,void 0);u(this,n,void 0);u(this,k,void 0);u(this,b,void 0);u(this,w,void 0);u(this,L,void 0);u(this,R,void 0);u(this,V,void 0);u(this,x,void 0);u(this,M,void 0);u(this,Q,void 0);u(this,E,void 0);u(this,S,void 0);u(this,P,new Set);this.options=s,c(this,y,t),c(this,R,null),this.bindMethods(),this.setOptions(s)}bindMethods(){this.refetch=this.refetch.bind(this)}onSubscribe(){this.listeners.size===1&&(e(this,n).addObserver(this),gt(e(this,n),this.options)?f(this,F,A).call(this):this.updateResult(),f(this,B,rt).call(this))}onUnsubscribe(){this.hasListeners()||this.destroy()}shouldFetchOnReconnect(){return ht(e(this,n),this.options,this.options.refetchOnReconnect)}shouldFetchOnWindowFocus(){return ht(e(this,n),this.options,this.options.refetchOnWindowFocus)}destroy(){this.listeners=new Set,f(this,K,nt).call(this),f(this,N,at).call(this),e(this,n).removeObserver(this)}setOptions(t,s){const r=this.options,h=e(this,n);if(this.options=e(this,y).defaultQueryOptions(t),this.options.enabled!==void 0&&typeof this.options.enabled!="boolean"&&typeof this.options.enabled!="function"&&typeof O(this.options.enabled,e(this,n))!="boolean")throw new Error("Expected enabled to be a boolean or a callback that returns a boolean");f(this,J,ot).call(this),e(this,n).setOptions(this.options),r._defaulted&&!tt(this.options,r)&&e(this,y).getQueryCache().notify({type:"observerOptionsUpdated",query:e(this,n),observer:this});const a=this.hasListeners();a&&mt(e(this,n),h,this.options,r)&&f(this,F,A).call(this),this.updateResult(s),a&&(e(this,n)!==h||O(this.options.enabled,e(this,n))!==O(r.enabled,e(this,n))||Y(this.options.staleTime,e(this,n))!==Y(r.staleTime,e(this,n)))&&f(this,W,et).call(this);const d=f(this,_,st).call(this);a&&(e(this,n)!==h||O(this.options.enabled,e(this,n))!==O(r.enabled,e(this,n))||d!==e(this,S))&&f(this,z,it).call(this,d)}getOptimisticResult(t){const s=e(this,y).getQueryCache().build(e(this,y),t),r=this.createResult(s,t);return _t(this,r)&&(c(this,b,r),c(this,L,this.options),c(this,w,e(this,n).state)),r}getCurrentResult(){return e(this,b)}trackResult(t,s){const r={};return Object.keys(t).forEach(h=>{Object.defineProperty(r,h,{configurable:!1,enumerable:!0,get:()=>(this.trackProp(h),s==null||s(h),t[h])})}),r}trackProp(t){e(this,P).add(t)}getCurrentQuery(){return e(this,n)}refetch({...t}={}){return this.fetch({...t})}fetchOptimistic(t){const s=e(this,y).defaultQueryOptions(t),r=e(this,y).getQueryCache().build(e(this,y),s);return r.isFetchingOptimistic=!0,r.fetch().then(()=>this.createResult(r,s))}fetch(t){return f(this,F,A).call(this,{...t,cancelRefetch:t.cancelRefetch??!0}).then(()=>(this.updateResult(),e(this,b)))}createResult(t,s){var dt;const r=e(this,n),h=this.options,a=e(this,b),d=e(this,w),g=e(this,L),U=t!==r?t.state:e(this,k),{state:C}=t;let o={...C},l=!1,p;if(s._optimisticResults){const v=this.hasListeners(),H=!v&&gt(t,s),Ct=v&&mt(t,r,s,h);(H||Ct)&&(o={...o,...Ft(C.data,t.options)}),s._optimisticResults==="isRestoring"&&(o.fetchStatus="idle")}let{error:D,errorUpdatedAt:j,status:m}=o;if(s.select&&o.data!==void 0)if(a&&o.data===(d==null?void 0:d.data)&&s.select===e(this,V))p=e(this,x);else try{c(this,V,s.select),p=s.select(o.data),p=bt(a==null?void 0:a.data,p,s),c(this,x,p),c(this,R,null)}catch(v){c(this,R,v)}else p=o.data;if(s.placeholderData!==void 0&&p===void 0&&m==="pending"){let v;if(a!=null&&a.isPlaceholderData&&s.placeholderData===(g==null?void 0:g.placeholderData))v=a.data;else if(v=typeof s.placeholderData=="function"?s.placeholderData((dt=e(this,M))==null?void 0:dt.state.data,e(this,M)):s.placeholderData,s.select&&v!==void 0)try{v=s.select(v),c(this,R,null)}catch(H){c(this,R,H)}v!==void 0&&(m="success",p=bt(a==null?void 0:a.data,v,s),l=!0)}e(this,R)&&(D=e(this,R),p=e(this,x),j=Date.now(),m="error");const T=o.fetchStatus==="fetching",q=m==="pending",G=m==="error",ct=q&&T,lt=p!==void 0;return{status:m,fetchStatus:o.fetchStatus,isPending:q,isSuccess:m==="success",isError:G,isInitialLoading:ct,isLoading:ct,data:p,dataUpdatedAt:o.dataUpdatedAt,error:D,errorUpdatedAt:j,failureCount:o.fetchFailureCount,failureReason:o.fetchFailureReason,errorUpdateCount:o.errorUpdateCount,isFetched:o.dataUpdateCount>0||o.errorUpdateCount>0,isFetchedAfterMount:o.dataUpdateCount>U.dataUpdateCount||o.errorUpdateCount>U.errorUpdateCount,isFetching:T,isRefetching:T&&!q,isLoadingError:G&&!lt,isPaused:o.fetchStatus==="paused",isPlaceholderData:l,isRefetchError:G&&lt,isStale:ut(t,s),refetch:this.refetch}}updateResult(t){const s=e(this,b),r=this.createResult(e(this,n),this.options);if(c(this,w,e(this,n).state),c(this,L,this.options),e(this,w).data!==void 0&&c(this,M,e(this,n)),tt(r,s))return;c(this,b,r);const h={},a=()=>{if(!s)return!0;const{notifyOnChangeProps:d}=this.options,g=typeof d=="function"?d():d;if(g==="all"||!g&&!e(this,P).size)return!0;const I=new Set(g??e(this,P));return this.options.throwOnError&&I.add("error"),Object.keys(e(this,b)).some(U=>{const C=U;return e(this,b)[C]!==s[C]&&I.has(C)})};(t==null?void 0:t.listeners)!==!1&&a()&&(h.listeners=!0),f(this,$,Ot).call(this,{...h,...t})}onQueryUpdate(){this.updateResult(),this.hasListeners()&&f(this,B,rt).call(this)}},y=new WeakMap,n=new WeakMap,k=new WeakMap,b=new WeakMap,w=new WeakMap,L=new WeakMap,R=new WeakMap,V=new WeakMap,x=new WeakMap,M=new WeakMap,Q=new WeakMap,E=new WeakMap,S=new WeakMap,P=new WeakMap,F=new WeakSet,A=function(t){f(this,J,ot).call(this);let s=e(this,n).fetch(this.options,t);return t!=null&&t.throwOnError||(s=s.catch(wt)),s},W=new WeakSet,et=function(){f(this,K,nt).call(this);const t=Y(this.options.staleTime,e(this,n));if(ft||e(this,b).isStale||!pt(t))return;const r=Qt(e(this,b).dataUpdatedAt,t)+1;c(this,Q,setTimeout(()=>{e(this,b).isStale||this.updateResult()},r))},_=new WeakSet,st=function(){return(typeof this.options.refetchInterval=="function"?this.options.refetchInterval(e(this,n)):this.options.refetchInterval)??!1},z=new WeakSet,it=function(t){f(this,N,at).call(this),c(this,S,t),!(ft||O(this.options.enabled,e(this,n))===!1||!pt(e(this,S))||e(this,S)===0)&&c(this,E,setInterval(()=>{(this.options.refetchIntervalInBackground||Et.isFocused())&&f(this,F,A).call(this)},e(this,S)))},B=new WeakSet,rt=function(){f(this,W,et).call(this),f(this,z,it).call(this,f(this,_,st).call(this))},K=new WeakSet,nt=function(){e(this,Q)&&(clearTimeout(e(this,Q)),c(this,Q,void 0))},N=new WeakSet,at=function(){e(this,E)&&(clearInterval(e(this,E)),c(this,E,void 0))},J=new WeakSet,ot=function(){const t=e(this,y).getQueryCache().build(e(this,y),this.options);if(t===e(this,n))return;const s=e(this,n);c(this,n,t),c(this,k,t.state),this.hasListeners()&&(s==null||s.removeObserver(this),t.addObserver(this))},$=new WeakSet,Ot=function(t){It.batch(()=>{t.listeners&&this.listeners.forEach(s=>{s(e(this,b))}),e(this,y).getQueryCache().notify({query:e(this,n),type:"observerResultsUpdated"})})},Rt);function Wt(i,t){return O(t.enabled,i)!==!1&&i.state.data===void 0&&!(i.state.status==="error"&&t.retryOnMount===!1)}function gt(i,t){return Wt(i,t)||i.state.data!==void 0&&ht(i,t,t.refetchOnMount)}function ht(i,t,s){if(O(t.enabled,i)!==!1){const r=typeof s=="function"?s(i):s;return r==="always"||r!==!1&&ut(i,t)}return!1}function mt(i,t,s,r){return(i!==t||O(r.enabled,i)===!1)&&(!s.suspense||i.state.status!=="error")&&ut(i,s)}function ut(i,t){return O(t.enabled,i)!==!1&&i.isStaleByTime(Y(t.staleTime,i))}function _t(i,t){return!tt(i.getCurrentResult(),t)}function zt(i=""){if(!Ut())throw new Error("vue-query hooks can only be used inside setup() function or functions that support injection context.");const t=Tt(i),s=Dt(t);if(!s)throw new Error("No 'queryClient' found in Vue context, use 'VueQueryPlugin' to properly initialize the library.");return s}function Bt(i,t,s){const r=zt(),h=Lt(()=>{const l=xt(t);typeof l.enabled=="function"&&(l.enabled=l.enabled());const p=r.defaultQueryOptions(l);return p._optimisticResults=r.isRestoring.value?"isRestoring":"optimistic",p}),a=new i(r,h.value),d=Mt(a.getCurrentResult());let g=()=>{};X(r.isRestoring,l=>{l||(g(),g=a.subscribe(p=>{yt(d,p)}))},{immediate:!0});const I=()=>{a.setOptions(h.value),yt(d,a.getCurrentResult())};X(h,I),At(()=>{g()});const U=(...l)=>(I(),d.refetch(...l)),C=()=>new Promise((l,p)=>{let D=()=>{};const j=()=>{if(h.value.enabled!==!1){a.setOptions(h.value);const m=a.getOptimisticResult(h.value);m.isStale?(D(),a.fetchOptimistic(h.value).then(l,T=>{vt(h.value.throwOnError,[T,a.getCurrentQuery()])?p(T):l(a.getCurrentResult())})):(D(),l(m))}};j(),D=X(h,j)});X(()=>d.error,l=>{if(d.isError&&!d.isFetching&&vt(h.value.throwOnError,[l,a.getCurrentQuery()]))throw l});const o=Pt(jt(d));for(const l in d)typeof d[l]=="function"&&(o[l]=d[l]);return o.suspense=C,o.refetch=U,o}function Kt(i,t){return Bt(Vt,i)}function Yt({enabled:i=!0}={}){return Kt({queryKey:["fieldTypes"],queryFn:async()=>{const{data:t}=await kt.get(route("microsites.fields.types"));return t},enabled:i})}export{Yt as u};