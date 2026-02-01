@extends('layouts.app')

@section('title', 'Abonnement')

@section('content')

<div class="min-h-screen bg-none flex flex-col items-center justify-top py-6">

    <!-- Formulaire d'abonnement -->
    <div class="container mx-auto p-4 border border-gray-500 p-5 rounded-2xl my-5 hidden">

        @if(session('error'))
            <div class="bg-red-100 text-red-700 p-2 mb-4 rounded">
                {{ session('error') }}
            </div>
        @endif

        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-2 mb-4 rounded">
                {{ session('success') }}
            </div>
        @endif

        
    </div>

    <div class="container bg-white border border-gray-200 rounded-lg overflow-hidden p-5 mt-10 mb-5">
        <h1 class="text-2xl font-bold mb-4 text-center">Nouvel Abonnement</h1>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-8">

            {{-- CAS 2 : Plusieurs Niveaux --}}
            <div id="multiple_levels" class="mb-4">
                <label class="block font-semibold mb-1">Niveaux</label>
                <div id="level_list">
                    {{-- Une ligne de niveau par défaut --}}
                    <div class="flex mb-2">
                        <select name="levels[]" class="border p-2 flex-1">
                            <option value="">-- Sélectionnez un niveau --</option>
                            @foreach($levels as $level)
                                <option value="{{ $level->id }}">{{ $level->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- type d'abonnement --}}
            <div id="multiple_subscription_types" class="mb-4">
                <label class="block font-semibold mb-1">Abonnement</label>
                <div id="subscription_type_list">
                    <div class="flex mb-2">
                        <select name="subscription_types[]" class="border p-2 flex-1">
                            <option value="">-- Sélectionnez un abonnement --</option>
                            <option value="CLASSIQUE">Classique (3 000 XAF)</option>
                            <option value="PREMIUM">Premium (6 000 XAF)</option>
                            <option value="EXCELLENCE">Excellence (8 000 XAF)</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Numéro de téléphone + mode de paiement --}}
            <div id="phone" class="mb-4">
                <label class="block font-semibold mb-1">Téléphone (Il s'agit du numéro avec lequel vous allez faire le dépot)</label>
                <input type="phone" name="phone" id="phone_val" class="font-bold" placeholder="237678905434">
            </div>

            
            {{-- <div id="total_price" class="mb-4">
                <label class="block font-semibold mb-1">Prix total</label>
                <input type="text" name="total_price" id="total_price_val" class="font-bold" value="0 XAF">
            </div> --}}
        </div>
        <form id="subscriptionForm" action="{{ route('subscriptions.store') }}" method="POST" class="space-y-4 py-5 flex justify-center">
                @csrf
                <button type="submit" class="cursor-pointer w-50 bg-[#03386a] hover:bg-[#0e243a] text-white font-semibold py-4 rounded text-lg transition">Soumettre</button>
            </form>
    </div>

</div>

<!-- Pop-up waiting finalisation-->
<div id="popup" class="fixed inset-0 bg-opacity-30 backdrop-blur-sm flex items-center justify-center z-50 hidden">
    <div class="bg-white p-20 rounded-lg shadow-lg text-center">
        <p class="mb-4 text-lg font-semibold">Abonnement en cours...</p>
        <svg class="animate-spin h-16 w-16 text-gray-300 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="text-white opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor"
                d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z">
            </path>
        </svg>
    </div>
</div>

<!-- Pop-up Confirmation-->
<div id="popup_confirm" class="fixed inset-0 bg-opacity-30 backdrop-blur-sm flex items-center justify-center z-50 hidden">
    <div class="bg-white p-20 rounded-lg shadow-lg text-center">
        <p class="mb-4 text-lg font-semibold">Abonnement reussi.</p>
        <svg class="w-16 h-16 mx-auto" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg" fill="#53eaa8" stroke="#53eaa8"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path fill="#53eaa8" d="M512 64a448 448 0 1 1 0 896 448 448 0 0 1 0-896zm-55.808 536.384-99.52-99.584a38.4 38.4 0 1 0-54.336 54.336l126.72 126.72a38.272 38.272 0 0 0 54.336 0l262.4-262.464a38.4 38.4 0 1 0-54.272-54.336L456.192 600.384z"></path></g></svg>
    </div>
</div>

<!-- Pop-up Warning-->
<div id="popup_warning" class="fixed inset-0 bg-opacity-30 backdrop-blur-sm flex items-center justify-center z-50 hidden"> 
    <div class="bg-white p-20 rounded-lg shadow-lg text-center">
        <svg class="w-16 h-16 mx-auto" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#f8c81b"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path fill-rule="evenodd" clip-rule="evenodd" d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10zm-1.5-5.009c0-.867.659-1.491 1.491-1.491.85 0 1.509.624 1.509 1.491 0 .867-.659 1.509-1.509 1.509-.832 0-1.491-.642-1.491-1.509zM11.172 6a.5.5 0 0 0-.499.522l.306 7a.5.5 0 0 0 .5.478h1.043a.5.5 0 0 0 .5-.478l.305-7a.5.5 0 0 0-.5-.522h-1.655z" fill="#f8c81b"></path></g></svg>
        <p class="mb-4 text-lg font-semibold">Abonnement en attente d'activation.</p>
        <div class="mt-6 text-left space-y-6">

            <!-- Étapes -->
            <div class="bg-gray-50 p-6 rounded-lg border">
                <h3 class="text-lg font-semibold mb-3 text-gray-800">Étapes d’activation de l’abonnement</h3>
                <ol class="list-decimal list-inside space-y-2 text-gray-700">
                    <li>Payer le montant de <b id="to_pay"></b><b> XAF</b> sur l’un des comptes ci-dessous.</li>
                    <li>Une fois votre paiement effectué, veuillez patienter pendant l’activation.</li>
                    <li>La validation de l’abonnement peut prendre jusqu’à <strong>24 heures</strong>.</li>
                </ol>
            </div>

            <!-- Moyens de paiement -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Orange Money -->
                <div class="border rounded-lg p-5 text-center shadow-sm hover:shadow-md transition">
                    <img 
                        src="https://upload.wikimedia.org/wikipedia/commons/c/c8/Orange_logo.svg"
                        alt="Orange Money"
                        class="h-12 mx-auto mb-4"
                    >
                    <p class="font-semibold text-gray-800">Orange Money</p>
                    <p class="text-gray-600 mt-1">Numéro de paiement</p>
                    <p class="text-lg font-bold text-orange-500 mt-2">
                        696090236
                    </p>
                    <p class="text-gray-600 mt-1 font-bold">POUOKAM NGUEGUIM</p>
                </div>

                <!-- MTN Mobile Money -->
                <div class="border rounded-lg p-5 text-center shadow-sm hover:shadow-md transition">
                    <img 
                        src="https://upload.wikimedia.org/wikipedia/commons/9/93/New-mtn-logo.jpg"
                        alt="MTN Mobile Money"
                        class="h-12 mx-auto mb-4"
                    >
                    <p class="font-semibold text-gray-800">MTN Mobile Money</p>
                    <p class="text-gray-600 mt-1">Numéro de paiement</p>
                    <p class="text-lg font-bold text-yellow-500 mt-2">
                        651993749
                    </p>
                    <p class="text-gray-600 mt-1 font-bold">POUOKAM NGUEGUIM</p>
                </div>

            </div>

            <!-- Note -->
            <p class="text-sm text-gray-500 text-center mt-4">
                ⚠️ Assurez-vous d’utiliser le même numéro que celui renseigner sur le formulaire pour faciliter la validation.
            </p>

        </div>

    </div>
</div>

<!-- Pop-up Error-->
<div id="popup_error" class="fixed inset-0 bg-opacity-30 backdrop-blur-sm flex items-center justify-center z-50 hidden">
    <div class="bg-white p-20 rounded-lg shadow-lg text-center">
        <svg class="w-16 h-16 mx-auto" viewBox="0 0 512 512" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#b20101" stroke="#b20101"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <title>error-filled</title> <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"> <g id="add" fill="#b20101" transform="translate(42.666667, 42.666667)"> <path d="M213.333333,3.55271368e-14 C331.136,3.55271368e-14 426.666667,95.5306667 426.666667,213.333333 C426.666667,331.136 331.136,426.666667 213.333333,426.666667 C95.5306667,426.666667 3.55271368e-14,331.136 3.55271368e-14,213.333333 C3.55271368e-14,95.5306667 95.5306667,3.55271368e-14 213.333333,3.55271368e-14 Z M262.250667,134.250667 L213.333333,183.168 L164.416,134.250667 L134.250667,164.416 L183.168,213.333333 L134.250667,262.250667 L164.416,292.416 L213.333333,243.498667 L262.250667,292.416 L292.416,262.250667 L243.498667,213.333333 L292.416,164.416 L262.250667,134.250667 Z" id="Combined-Shape"> </path> </g> </g> </g></svg>
        <p class="mb-4 text-lg font-semibold">Une erreur est survenue, Vérifier que cet abonement n'est pas déjà en cour puis contacter l'administrateur.</p>
        <button class="close_error cursor-pointer w-50 bg-[#b20101] hover:bg-red-500 text-white font-semibold py-4 rounded text-lg transition">Fermer</button>
    </div>
</div>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        const popup = document.getElementById("popup");
        const popup_confirm = document.getElementById("popup_confirm");
        const popup_warning = document.getElementById("popup_warning");
        const popup_error = document.getElementById("popup_error");
        const closeWarning = document.querySelector(".close_warning"); 
        const closeError = document.querySelector(".close_error"); 
        const addBtn = document.querySelector(".add_chart"); // bouton ajouter
        const levelList = document.querySelector("#level_list");
        const subjectList = document.querySelector("#subject_list");
        const phone = document.querySelector("#phone");
        const subscriptionTypeList = document.querySelector("#subscription_type_list");
        const tbody = document.querySelector("tbody");
        const total_price_val = document.querySelector("#total_price_val");

        

        const prices = {
            CLASSIQUE : 3000,
            PREMIUM: 6000,
            EXCELLENCE: 8000
        }


        function form_data(levelValue, subscriptionTypeValue, phone) {
             const formData = new FormData();

            // selectedLevels.forEach(id => {
            //     formData.append('levels[]', id);
            // });

            // selectedSubjects.forEach(id => {
            //     formData.append('subjects[]', id);
            // });

            // selectedSubscriptions.forEach(id => {
            //     formData.append('subscription_types[]', id);
            // });

            formData.append('level', levelValue);
            formData.append('subscription_type', subscriptionTypeValue);
            formData.append('phone', phone);
            formData.append('price', prices[subscriptionTypeValue]);

            formData.append('payment_method', 'mtn');

            return formData;
        }

        document.querySelector("#subscriptionForm").addEventListener("submit", function (e) {
            e.preventDefault();
            const phone_val = document.querySelector("#phone_val");
            // Récupérer l'abonnement sélectionnée
            const subscriptionTypeSelect = subscriptionTypeList.querySelector("select");
            const subscriptionTypeText = subscriptionTypeSelect.options[subscriptionTypeSelect.selectedIndex].text;
            const subscriptionTypeValue = subscriptionTypeSelect.value;
            
            const levelSelect = levelList.querySelector("select");
            const levelText = levelSelect.options[levelSelect.selectedIndex].text;
            const levelValue = levelSelect.value;


            if (levelValue.length === 0 || subscriptionTypeValue.length === 0) {
                alert("Veuillez remplir tout les champs.");
                return;
            }

            popup.classList.remove("hidden");

            const formData = form_data(levelValue, subscriptionTypeValue, phone_val.value);

            fetch(this.action, {
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(async response => {

                // 🔐 Non authentifié → redirection login
                if (response.status === 401) {
                    window.location.href = "{{ route('login') }}";
                    return;
                }

                // ❌ Autres erreurs HTTP
                if (!response.ok) {
                    closeError.addEventListener("click", function (e) {
                        popup_error.classList.add("hidden");
                    })
                    popup.classList.add("hidden");
                    popup_error.classList.remove("hidden");
                    const errorData = await response.json();
                    throw errorData;
                }

                popup.classList.add("hidden");
                document.querySelector('#to_pay').innerHTML = prices[subscriptionTypeList.querySelector("select").value]
                popup_warning.classList.remove("hidden");

                // ✅ Succès
                return response.json();
            })
            
            .catch(error => {
                console.error("Erreur :", error);
            })
            .finally(() => {
                
            });
        });

    });

</script>


@endsection