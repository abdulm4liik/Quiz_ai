import{_ as c}from"./AIresponse-92e00b7d.js";import{_ as p}from"./Upload-3ff245ae.js";import{_ as u,r as m,a as f,o as v,b as x,w as l,d as s,e as _,t as y}from"./index-e0e7d705.js";const h={class:"p-6"},w={class:"flex"},g={class:"py-6"},b={class:"max-w-7xl mx-auto sm:px-6 lg:px-8"},S={class:"bg-light-gray overflow-hidden shadow-sm sm:rounded-lg border"},C={class:"p-10 text-navy-soft"},k={class:"mb-6"},B=0,D={__name:"Summary",setup(F){const n=m(""),a=m(!1),i=o=>{var e,t,d;(d=(t=(e=o==null?void 0:o.data)==null?void 0:e.response_data)==null?void 0:t.summary)!=null&&d.summary&&(n.value=o.data.response_data.summary.summary,a.value=!0)},r=()=>{a.value=!1};return(o,e)=>{const t=f("Modal");return v(),x(c,null,{header:l(()=>e[1]||(e[1]=[s("div",{class:"font-semibold text-2xl text-navy leading-tight bg-white w-full sm:w-1/2 md:w-1/4 p-4 sm:p-2 text-center rounded-md"}," AI Summary Dashboard ",-1)])),default:l(()=>[_(t,{show:a.value,onClose:r},{default:l(()=>[s("div",h,[e[0]||(e[0]=s("h3",{class:"text-xl font-semibold text-navy pb-2"},"Summary",-1)),s("div",w,y(n.value),1),s("div",{class:"flex justify-center"},[s("button",{onClick:r,class:"mt-4 px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-400 transition w-full"}," Close ")])])]),_:1},8,["show"]),s("div",g,[s("div",b,[s("div",S,[s("div",C,[s("div",k,[_(p,{responseType:B,onFileUploadSuccess:i})])])])])])]),_:1})}}},T=u(D,[["__scopeId","data-v-f7a24f34"]]);export{T as default};
