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
            
            {{-- CAS 2 : Plusieurs matières --}}
            <div id="multiple_subjects" class="mb-4">
                <label class="block font-semibold mb-1">Matières</label>
                <div id="subject_list">
                    {{-- Une ligne de matière par défaut --}}
                    <div class="flex mb-2">
                        <select name="subjects[]" class="border p-2 flex-1">
                            <option value="">-- Sélectionnez une matière --</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            {{-- Numéro de téléphone + mode de paiement --}}
            <div id="phone" class="mb-4">
                <label class="block font-semibold mb-1">Téléphone</label>
                <input type="phone" name="phone" id="phone_val" class="font-bold" placeholder="237678905434">
            </div>

            
            <div id="total_price" class="mb-4">
                <label class="block font-semibold mb-1">Prix total</label>
                <input type="text" name="total_price" id="total_price_val" class="font-bold" value="0 XAF">
            </div>
        </div>
        <button class="add_chart cursor-pointer w-fit bg-[#03386a] hover:bg-[#0e243a] text-white font-semibold p-4 rounded text-lg transition">Ajouter l'abonnement</button>
        
        <div class="overflow-x-auto mt-8">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Utilisateur</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Niveau</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Matière</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                        
                </tbody>
            </table>
        </div>

        <div>
            <form id="subscriptionForm" action="{{ route('subscriptions.store') }}" method="POST" class="space-y-4 py-5 flex justify-center">
                @csrf
                <button type="submit" class="cursor-pointer w-50 bg-[#03386a] hover:bg-[#0e243a] text-white font-semibold py-4 rounded text-lg transition">Soumettre</button>
            </form>
        </div>
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
        <svg viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg" fill="#53eaa8" stroke="#53eaa8"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path fill="#53eaa8" d="M512 64a448 448 0 1 1 0 896 448 448 0 0 1 0-896zm-55.808 536.384-99.52-99.584a38.4 38.4 0 1 0-54.336 54.336l126.72 126.72a38.272 38.272 0 0 0 54.336 0l262.4-262.464a38.4 38.4 0 1 0-54.272-54.336L456.192 600.384z"></path></g></svg>
    </div>
</div>

<!-- Pop-up Warning-->
<div id="popup_warning" class="fixed inset-0 bg-opacity-30 backdrop-blur-sm flex items-center justify-center z-50 hidden">
    <div class="bg-white p-20 rounded-lg shadow-lg text-center">
        <p class="mb-4 text-lg font-semibold">Abonnement déjà en cours.</p>
        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#f8c81b"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path fill-rule="evenodd" clip-rule="evenodd" d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10zm-1.5-5.009c0-.867.659-1.491 1.491-1.491.85 0 1.509.624 1.509 1.491 0 .867-.659 1.509-1.509 1.509-.832 0-1.491-.642-1.491-1.509zM11.172 6a.5.5 0 0 0-.499.522l.306 7a.5.5 0 0 0 .5.478h1.043a.5.5 0 0 0 .5-.478l.305-7a.5.5 0 0 0-.5-.522h-1.655z" fill="#f8c81b"></path></g></svg>
        <button class="close_warning cursor-pointer w-50 bg-[#b20101] hover:bg-red-500 text-white font-semibold py-4 rounded text-lg transition">Fermer</button>
    </div>
</div>

<!-- Pop-up Error-->
<div id="popup_error" class="fixed inset-0 bg-opacity-30 backdrop-blur-sm flex items-center justify-center z-50 hidden">
    <div class="bg-white p-20 rounded-lg shadow-lg text-center">
        <p class="mb-4 text-lg font-semibold">Une erreur est survenue, veuillez réessayer plus tard ou contacter l'administrateur.</p>
        <svg viewBox="0 0 512 512" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#b20101" stroke="#b20101"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <title>error-filled</title> <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"> <g id="add" fill="#b20101" transform="translate(42.666667, 42.666667)"> <path d="M213.333333,3.55271368e-14 C331.136,3.55271368e-14 426.666667,95.5306667 426.666667,213.333333 C426.666667,331.136 331.136,426.666667 213.333333,426.666667 C95.5306667,426.666667 3.55271368e-14,331.136 3.55271368e-14,213.333333 C3.55271368e-14,95.5306667 95.5306667,3.55271368e-14 213.333333,3.55271368e-14 Z M262.250667,134.250667 L213.333333,183.168 L164.416,134.250667 L134.250667,164.416 L183.168,213.333333 L134.250667,262.250667 L164.416,292.416 L213.333333,243.498667 L262.250667,292.416 L292.416,262.250667 L243.498667,213.333333 L292.416,164.416 L262.250667,134.250667 Z" id="Combined-Shape"> </path> </g> </g> </g></svg>
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
        const tbody = document.querySelector("tbody");
        const total_price_val = document.querySelector("#total_price_val");

        // Tableaux de stockage
        let selectedLevels = [];
        let selectedSubjects = [];
        let unit_price = 10000
        let unit_price_reduction = 1000

        addBtn.addEventListener("click", function(e) {
            e.preventDefault();
            

            // Récupérer le niveau sélectionné
            const levelSelect = levelList.querySelector("select");
            const levelText = levelSelect.options[levelSelect.selectedIndex].text;
            const levelValue = levelSelect.value;

            // Récupérer la matière sélectionnée
            const subjectSelect = subjectList.querySelector("select");
            const subjectText = subjectSelect.options[subjectSelect.selectedIndex].text;
            const subjectValue = subjectSelect.value;

            if(!levelValue || !subjectValue){
                // alert("Veuillez sélectionner un niveau et une matière.");
                return;
            }

            if (selectedLevels.includes(levelValue) && selectedSubjects.includes(subjectValue)) {
                // alert("Cet abonnement existe déjà.");
                return;
            }

            // Ajouter les IDs dans les tableaux
            selectedLevels.push(levelValue);
            selectedSubjects.push(subjectValue);

            // Debug (optionnel)
            // console.log("Niveaux sélectionnés :", selectedLevels);
            // console.log("Matières sélectionnées :", selectedSubjects);

            // Créer une nouvelle ligne
            const tr = document.createElement("tr");
            tr.classList.add("hover:bg-gray-50");
            tr.dataset.levelId = levelValue;
            tr.dataset.subjectId = subjectValue;
            tr.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="text-sm font-medium text-gray-900">{{ auth()->user()->name?? '' }}</div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                        ${levelText}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">${subjectText}</td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <div class="flex items-center justify-center space-x-2 text-red-600">
                        <a href="#" class="delete-row">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 
                                        01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 
                                        0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                </path>
                            </svg>
                        </a>
                    </div>
                </td>
            `;
            tbody.appendChild(tr);

            // Ajouter l'événement supprimer à ce nouvel élément
            tr.querySelector(".delete-row").addEventListener("click", function(ev){
                ev.preventDefault();
                // Supprimer les IDs des tableaux
                selectedLevels = selectedLevels.filter(id => id !== tr.dataset.levelId);
                selectedSubjects = selectedSubjects.filter(id => id !== tr.dataset.subjectId);

                tr.remove();

                total_price_val.value = calculate_total_price();

                // console.log("Niveaux après suppression :", selectedLevels);
                // console.log("Matières après suppression :", selectedSubjects);
                
            });
            
            total_price_val.value = calculate_total_price();
        });

        // Supprimer une ligne existante
        document.querySelectorAll(".delete-row").forEach(function(btn){
            btn.addEventListener("click", function(e){
                e.preventDefault();
                btn.closest("tr").remove();
            
                total_price_val.value = calculate_total_price();
            });
            
        });

        function calculate_total_price() {
            let unit = 0
            if (selectedLevels.length <= 1) {
                unit = unit_price
            } else {
                unit = unit_price - unit_price_reduction
            }

            return selectedLevels.length*unit
        }

        function form_data() {
             const formData = new FormData();

            selectedLevels.forEach(id => {
                formData.append('levels[]', id);
            });

            selectedSubjects.forEach(id => {
                formData.append('subjects[]', id);
            });

            formData.append('unit_price', unit_price);

            formData.append('payment_method', 'mtn');

            return formData;
        }

        document.querySelector("#subscriptionForm").addEventListener("submit", function (e) {
            e.preventDefault();
            const phone_val = document.querySelector("#phone_val");

            if (selectedLevels.length === 0 || selectedSubjects.length === 0) {
                alert("Veuillez ajouter au moins un abonnement.");
                return;
            }

            popup.classList.remove("hidden");

            const formData = form_data();

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

                // ✅ Succès
                return response.json();
            })
            .then(data => {
                if (!data) return;

                console.log("Succès :", data);
                return fetch("{{ route('initiate') }}", {
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        // subscription_ids: data.subscription_ids,
                        provider: "mtn", // ou "orange"
                        phone: phone_val.value,
                        payment_method: "mtn",
                    })
                });
            })
            .then(async response => {

                if (!response) return;

                if (response.status === 401) {
                    window.location.href = "{{ route('login') }}";
                    return;
                }

                if (response.status === 400) {
                    closeWarning.addEventListener("click", function (e) {
                        popup_warning.classList.add("hidden");
                    })
                    popup.classList.add("hidden");
                    popup_warning.classList.remove("hidden");
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

                return response.json();
            })
            .then(paymentData => {
                if (!paymentData) return;

                console.log("Paiement lancé :", paymentData);

                // alert("Paiement initié. Vérifiez votre téléphone 📱");
                popup.classList.add("hidden");
                popup_confirm.classList.remove("hidden");
                setTimeout(() => {
                    window.location.href = "{{ route('home') }}";
                }, 300);
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