<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Wooflix - Wholesale Inquiry</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        :root {
            --primary-orange: #ff6b35;
            --primary-dark: #2d3436;
            --bg-soft: #f4f7f6;
        }

        body {
            background-color: var(--bg-soft);
            color: var(--primary-dark);
            font-family: 'Figtree', sans-serif;
        }

        .wholesale-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .form-container {
            width: 100%;
            max-width: 550px;
            background: #ffffff;
            padding: 50px 40px;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.06);
            position: relative;
            overflow: hidden;
        }

        .progress-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 6px;
            background: #eee;
        }
        .progress-bar-fill {
            height: 100%;
            background: var(--primary-orange);
            transition: width 0.4s ease;
        }

        .form-control {
            font-size: 1.1rem;
            padding: 12px 20px;
            border: 2px solid #edf2f7;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-orange);
            box-shadow: 0 0 0 4px rgba(255, 107, 53, 0.1);
            outline: none;
        }

        .custom-option {
            display: block;
            padding: 15px;
            margin-bottom: 10px;
            border: 2px solid #edf2f7;
            border-radius: 12px;
            cursor: pointer;
            text-align: center;
            transition: all 0.2s;
            background: white;
            user-select: none;
        }
        .custom-option:hover {
            border-color: var(--primary-orange);
        }
        .custom-option.selected {
            border-color: var(--primary-orange);
            background: rgba(255, 107, 53, 0.08);
            color: var(--primary-orange);
            font-weight: 600;
        }

        .btn-orange {
            background: var(--primary-orange);
            color: white;
            padding: 12px 30px;
            border-radius: 10px;
            font-weight: 600;
            border: none;
        }
        .btn-orange:hover:not(:disabled) {
            background: #e85a2a;
            color: white;
            transform: translateY(-1px);
        }
        .btn-orange:disabled {
            background: #ccc;
            cursor: not-allowed;
        }
        .btn-light {
            background: #f8f9fa;
            color: #636e72;
            padding: 12px 30px;
            border-radius: 10px;
            font-weight: 600;
            border: 1px solid #ddd;
        }
    </style>
</head>
<body class="antialiased">

<div class="wholesale-wrapper" x-data="wholesaleForm()">
    <div class="form-container">
        <div class="progress-container">
            <div class="progress-bar-fill" :style="'width: ' + ((step + 1) / totalSteps * 100) + '%'"></div>
        </div>

        <template x-if="submitted">
            <div class="text-center py-4" x-transition>
                <div class="mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor" class="text-success bi bi-check-circle-fill" viewBox="0 0 16 16">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                    </svg>
                </div>
                <h2 class="fw-bold text-success">Request Sent!</h2>
                <p class="text-muted">Thank you. We will review your business details and send the wholesale pricing guide to your email shortly.</p>
                <button class="btn btn-orange mt-3" @click="window.location.href = '/'">Back to Home</button>
            </div>
        </template>

        <template x-if="!submitted">
            <div class="text-center">
                <small class="text-uppercase text-muted fw-bold mb-2 d-block" x-text="'Step ' + (step + 1) + ' of ' + totalSteps"></small>
                
                <div :key="step" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4">
                    <h3 class="mb-4 fw-bold" x-text="current.label"></h3>

                    <div class="mb-5" style="min-height: 120px; display: flex; align-items: center; justify-content: center;">
                        <div class="w-100">
                            <template x-if="['text', 'email', 'tel'].includes(current.type)">
                                <input :type="current.type" class="form-control text-center" x-model="form[current.name]" :placeholder="current.placeholder || ''" @keydown.enter="next">
                            </template>

                            <template x-if="current.type === 'select'">
                                <select class="form-control" x-model="form[current.name]">
                                    <option value="">Select an option...</option>
                                    <template x-for="opt in current.options" :key="opt">
                                        <option :value="opt" x-text="opt"></option>
                                    </template>
                                </select>
                            </template>

                            <template x-if="current.type === 'checkbox-group'">
                                <div class="row g-2">
                                    <template x-for="opt in current.options" :key="opt">
                                        <div class="col-6">
                                            <div class="custom-option" :class="form[current.name].includes(opt) ? 'selected' : ''" @click="toggleMulti(current.name, opt)">
                                                <span class="fw-medium" x-text="opt"></span>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </template>

                            <template x-if="current.type === 'radio'">
                                <div class="d-flex justify-content-center gap-3">
                                    <button class="btn px-5 py-3 fw-bold" :class="form[current.name] === true ? 'btn-orange' : 'btn-light'" @click="form[current.name] = true">Yes</button>
                                    <button class="btn px-5 py-3 fw-bold" :class="form[current.name] === false ? 'btn-orange' : 'btn-light'" @click="form[current.name] = false">No</button>
                                </div>
                            </template>

                            <template x-if="current.type === 'textarea'">
                                <textarea class="form-control" rows="3" x-model="form[current.name]" placeholder="Enter your requirements..."></textarea>
                            </template>

                            <template x-if="current.type === 'consent'">
                                <div class="form-check text-start d-inline-block p-3 border rounded">
                                    <input class="form-check-input" type="checkbox" id="consent" x-model="form.consent">
                                    <label class="form-check-label ms-2 cursor-pointer" for="consent" x-text="current.labelText"></label>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <button type="button" class="btn btn-light" @click="prev" x-show="step > 0" style="display: none;">Back</button>
                    <div x-show="step === 0"></div>
                    
                    <button type="button" class="btn btn-orange px-4" @click="next" :disabled="loading || !isValid">
                        <span x-show="!loading" x-text="step === totalSteps - 1 ? 'Request Wholesale Pricing' : 'Next'"></span>
                        <span x-show="loading" class="spinner-border spinner-border-sm"></span>
                    </button>
                </div>
            </div>
        </template>
    </div>
</div>

<script>
function wholesaleForm() {
    return {
        step: 0,
        submitted: false,
        loading: false,
        form: {
            full_name: '', business_name: '', email: '', phone: '',
            business_type: '', gst_number: '', address: '', city: '', state: '', postal_code: '',
            products_interested: [], monthly_quantity: '',
            sells_pet_products: null, brands: '',
            sales_channels: [], message: '', consent: false
        },
        allFields: [
            { name: 'full_name', label: 'What is your full name?', type: 'text', required: true },
            { name: 'business_name', label: 'What is your business name?', type: 'text', required: true },
            { name: 'email', label: 'Email Address', type: 'email', required: true },
            { name: 'phone', label: 'Phone Number / WhatsApp', type: 'tel', required: true },
            { name: 'business_type', label: 'Business Type', type: 'select', options: ['Pet Store', 'Veterinary Clinic', 'Grooming Salon', 'Distributor', 'Online Seller', 'Other'], required: true },
            { name: 'gst_number', label: 'GST Number (Optional)', type: 'text', required: false },
            { name: 'address', label: 'Business Address', type: 'text', required: true },
            { name: 'city', label: 'City', type: 'text', required: true },
            { name: 'state', label: 'State', type: 'text', required: true },
            { name: 'postal_code', label: 'PIN Code', type: 'text', required: true },
            { name: 'products_interested', label: 'Products Interested In', type: 'checkbox-group', options: ['Collars', 'Leashes', 'Harness', 'Pee Pads', 'Grooming Products', 'Rollers', 'All Products'], required: true },
            { name: 'monthly_quantity', label: 'Estimated Monthly Quantity', type: 'select', options: ['0-50 units', '50-100 units', '100-300 units', '300-1000 units', '1000+ units'], required: true },
            { name: 'sells_pet_products', label: 'Do you currently sell pet products?', type: 'radio', required: true },
            { name: 'brands', label: 'Which brands do you carry?', type: 'text', condition: (form) => form.sells_pet_products === true, required: false },
            { name: 'sales_channels', label: 'Sales Channels', type: 'checkbox-group', options: ['Offline Store', 'Online Sellers', 'Own Website', 'Distributor Network'], required: true },
            { name: 'message', label: 'Additional Information', type: 'textarea', required: false },
            { name: 'consent', label: 'Agreement', type: 'consent', labelText: 'I agree to be contacted by Wooflix regarding wholesale opportunities', required: true }
        ],

        get fields() {
            return this.allFields.filter(f => !f.condition || f.condition(this.form));
        },
        get current() { return this.fields[this.step]; },
        get totalSteps() { return this.fields.length; },
        
        get isValid() {
            const f = this.current;
            if (!f.required) return true;
            
            const value = this.form[f.name];
            
            if (f.type === 'checkbox-group') return value.length > 0;
            if (f.type === 'consent') return value === true;
            if (f.type === 'radio') return value !== null;
            
            return value && value.toString().trim() !== '';
        },

        next() {
            if (!this.isValid) return;
            if (this.step < this.totalSteps - 1) {
                this.step++;
            } else {
                this.submit();
            }
        },
        
        prev() { if (this.step > 0) this.step--; },
        
        toggleMulti(field, value) {
            if (this.form[field].includes(value)) {
                this.form[field] = this.form[field].filter(v => v !== value);
            } else {
                this.form[field].push(value);
            }
        },

        submit() {
            this.loading = true;
            fetch('{{ route("front.wholesale.save") }}', {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json', 
                    'Accept': 'application/json', 
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content 
                },
                body: JSON.stringify(this.form)
            })
            .then(res => {
                if (res.ok) {
                    this.submitted = true;
                } else {
                    alert('Submission failed. Please check your internet connection and try again.');
                }
            })
            .catch(() => alert('Something went wrong.'))
            .finally(() => this.loading = false);
        }
    }
}
</script>
</body>
</html>