
@props(['message'])


<div id="alertMessage" class="bg-yellow-100 text-yellow-700 p-5 mb-5 rounded flex items-center transition ease-in">
    <svg class="w-8 h-8 mx-auto" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#f8c81b"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path fill-rule="evenodd" clip-rule="evenodd" d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10zm-1.5-5.009c0-.867.659-1.491 1.491-1.491.85 0 1.509.624 1.509 1.491 0 .867-.659 1.509-1.509 1.509-.832 0-1.491-.642-1.491-1.509zM11.172 6a.5.5 0 0 0-.499.522l.306 7a.5.5 0 0 0 .5.478h1.043a.5.5 0 0 0 .5-.478l.305-7a.5.5 0 0 0-.5-.522h-1.655z" fill="#f8c81b"></path></g></svg>
    <span class="ml-2">{{$message}}</span>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const popup = document.getElementById("alertMessage");
        setTimeout(() => {
            popup.classList.add("hidden");
        }, 5000);
    });
</script>