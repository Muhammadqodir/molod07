<div
    x-data="{ show: false, message: '', timeout: null }"
    x-on:notify.window="clearTimeout(timeout); message = $event.detail; show = true; timeout = setTimeout(() => show = false, 3000)"
    x-show="show"
    x-transition
    class="fixed z-[9999999999999] top-[86px] right-6 bg-primary text-white px-4 py-3 rounded-xl shadow-lg text-sm z-50"
    style="display: none;"
>
    <x-lucide-info class="inline-block w-4 h-4 mr-2" />
    <span x-text="message"></span>
</div>