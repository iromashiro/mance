@extends('layouts.app')
@section('title', 'Aruna AI MEMBARA')
@section('content')

<style>
    .chat-frame {
        width: 100%;
        height: clamp(420px, 80svh, 900px);
        border: 0;
        display: block;
        border-radius: 12px;
    }
</style>

<iframe id="aruna-chat" class="chat-frame" src="https://aruna.muaraenimkab.go.id/chat/yhBYeUeHUHks01pJ"
    title="Aruna AI Chat" loading="lazy" allow="microphone; clipboard-write"></iframe>

<script>
    (function(){
    const iframe = document.getElementById('aruna-chat');

    function hidePoweredBy(doc){
      // sisipkan CSS global untuk menyembunyikan badge
      const style = doc.createElement('style');
      style.textContent = `
        .powered-by,
        [data-testid="powered-by"],
        [class*="poweredBy"],
        [class*="powered-by"]{ display:none!important; visibility:hidden!important; }
      `;
      doc.head.appendChild(style);
    }

    iframe.addEventListener('load', ()=>{
      try{
        const doc = iframe.contentDocument || iframe.contentWindow.document;
        hidePoweredBy(doc);

        // kalau UI Dify merender ulang, pastikan tetap hidden
        const mo = new MutationObserver(()=> hidePoweredBy(doc));
        mo.observe(doc.documentElement, { childList:true, subtree:true });
      }catch(e){
        // kalau sampai error di sini, berarti cross-origin -> tidak bisa
        console.warn('Tidak bisa akses iframe (cross-origin). Perlu patch di sumber Dify.');
      }
    });
  })();
</script>

@endsection