@include('CDN_Header')
@include('navbar')


{{-- =========================
HERO SECTION (with “1 Day Left” badge)
======================== --}}
<section id="hero" class="relative h-screen bg-cover bg-center overflow-hidden"
  style="background-image: url('{{ asset('../img/LifeHealer/user1.png') }}');">
  <div class="absolute inset-0 bg-black/70"></div>
  <div
    class="relative z-10 flex flex-col items-start justify-center h-full max-w-4xl mx-auto px-6 space-y-6 animate-fade-in">
    <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-extrabold text-white leading-tight drop-shadow-lg">
      <span class="block animate-fade-in delay-200">10X Your Power Of Manifestation</span>
      <span class="block animate-fade-in delay-400">Within 2 Hours</span>
    </h1>
    <p class="mt-4 text-lg sm:text-xl text-gray-300 max-w-2xl animate-fade-in delay-600">
      Unlock your true potential by learning powerful manifestation techniques that can transform your life in just 2
      hours.
      Join us for an enlightening session that will change your approach to achieving your goals.
    </p>
    <div class="mt-8 flex items-center space-x-4 animate-fade-in delay-800">
      <a href="#pricing"
        class="inline-block bg-gradient-to-r from-yellow-400 to-pink-500 text-black font-semibold rounded-full px-8 py-4 shadow-lg transform hover:scale-105 transition-transform duration-300">
        Enroll Now •
        <span class="ml-2"><i class="fa fa-arrow-right"></i></span>
      </a>
      {{-- Dynamic “Days Left” Badge --}}
      <!-- <span id="daysLeftBadge" class="bg-red-500 text-white font-semibold rounded-full px-4 py-2 shadow-lg"> -->
        <!-- JS will insert “1 Day Left” -->
      <!-- </span> -->
    </div>

    <div class="mt-8 flex flex-wrap items-center space-x-6 text-gray-300">
      <div class="flex items-center space-x-1">
        @for ($i = 0; $i < 5; $i++)
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400 animate-pulse" viewBox="0 0 20 20"
        fill="currentColor">
        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 
       0l1.286 3.966a1 1 0 00.95.69h4.162c.969 
       0 1.371 1.24.588 1.81l-3.37 2.452a1 1 
       0 00-.364 1.118l1.285 3.965c.3.922-.755 
       1.688-1.54 1.118l-3.37-2.451a1 1 0 
       00-1.175 0l-3.37 2.451c-.784.57-1.838-.196-1.539-1.118l1.286-3.965a1 1 
       0 00-.364-1.118L2.713 9.393c-.783-.57-.38-1.81.588-1.81h4.162a1 1 
       0 00.951-.69l1.286-3.966z" />
      </svg>
    @endfor
      </div>
      <span class="text-sm animate-fade-in delay-1000">4.9/5 (12,000+ Reviews)</span>
      <span class="text-sm animate-fade-in delay-1200">100K+ Lives Transformed</span>
    </div>
  </div>
</section>

{{-- =========================
ABOUT THE COURSE
======================== --}}
<section id="about-course" class="py-20 bg-gradient-to-r from-purple-700 to-pink-600">
  <div class="max-w-5xl mx-auto px-6 text-center">
    <h2 class="text-3xl sm:text-4xl font-bold text-white mb-4 animate-fade-in">About This Masterclass</h2>
    <p class="mt-4 text-lg text-gray-200 animate-fade-in delay-200">
      Revealing the “4D Success Framework” that will help you get unstuck from an unfulfilling corporate job, restart
      your career at any age, and find your true calling, happiness & peace! Career growth, income hike, dream job
      manifestation, unexpected promotion - ready to push the restart button of your career.
    </p>

    <div class="mt-12 grid sm:grid-cols-1 md:grid-cols-2 gap-10">
      @php
    $modules = [
      ['Mindset Reset', 'Learn how to eliminate self‐limiting beliefs, program your subconscious, and cultivate unstoppable confidence.'],
      ['Career & Purpose', 'Discover your true calling, unlock peak performance techniques, and attract success effortlessly.'],
      ['Meditative Healing', 'Master powerful meditation practices that accelerate physical healing, reduce stress, and heighten intuition.'],
      ['Manifestation Accelerator', 'Use guided visualization, affirmations, and energy‐alignment techniques to manifest wealth, relationships, and joy.'],
    ];
    @endphp

      @foreach($modules as $idx => $mod)
      <div
      class="bg-white/10 rounded-2xl p-6 shadow-lg transform hover:-translate-y-2 hover:shadow-2xl transition-all duration-300 animate-fade-in delay-{{ 200 * ($idx + 1) }}">
      <h3 class="font-semibold text-xl text-yellow-300 mb-3 flex items-center space-x-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-300" fill="none" viewBox="0 0 24 24"
        stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        <span>{{ $mod[0] }}</span>
      </h3>
      <p class="text-gray-200">{{ $mod[1] }}</p>
      </div>
    @endforeach
    </div>
  </div>
</section>

{{-- =========================
BENEFITS
======================== --}}
<section id="benefits" class="py-20 bg-gray-100">
  <div class="max-w-7xl mx-auto px-6">
    <h2 class="text-3xl sm:text-4xl font-bold text-gray-800 text-center animate-fade-in">Why You Should Join</h2>
    <p class="mt-4 text-lg text-gray-600 text-center max-w-3xl mx-auto animate-fade-in delay-200">
      Over <strong>100,000</strong> men and women have used this exact formula to transform their careers,
      health, relationships, and inner peace. Here’s what you’ll gain:
    </p>

    <div class="mt-12 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-10">
      @php
    $benefits = [
      ['Break Free from Burnout', 'Eliminate stress, anxiety, and overwhelm—step into a state of calm, clarity, and unstoppable energy.', 'M5 13l4 4L19 7'],
      ['Skyrocket Your Career', 'Attract promotions, salary hikes, or new dream‐job offers—no matter your age, no matter your background.', 'M3 10h18M7 6h10M12 2v20'],
      ['Deep Healing & Peace', 'Use scientifically proven meditation and energy practices to heal body, mind, and spirit—instantly.', 'M3 7l5 5-5 5M19 7l-5 5 5 5'],
      ['Manifest Anything', 'Program your subconscious mind to attract wealth, love, health, and happiness—so you can live life on your terms.', 'M17 20h5V4H2v16h5m10 0V4M7 8h.01M7 12h.01M7 16h.01'],
      ['Video Access', 'Rewatch the entire course any time—plus receive all future updates at no extra cost.', 'M12 8c-2.21 0-4 1.79-4 4 0 1.657 1.007 3.066 2.4 3.584L12 22l1.6-6.416C14.993 15.066 16 13.657 16 12c0-2.21-1.79-4-4-4z'],
      ['24/7 Support', 'Our dedicated team is available around the clock to answer questions and guide you on your journey.', 'M3 10h18M7 6h10M12 2v20'],
    ];
    @endphp

      @foreach($benefits as $idx => $ben)
      <div
      class="bg-white rounded-2xl p-6 text-center shadow-lg hover:-translate-y-2 hover:shadow-2xl transition-all duration-300 animate-fade-in delay-{{ 200 * ($idx + 1) }}">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-pink-500 mb-4" fill="none"
        viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $ben[2] }}" />
      </svg>
      <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ $ben[0] }}</h3>
      <p class="text-gray-600">{{ $ben[1] }}</p>
      </div>
    @endforeach
    </div>
  </div>
</section>

{{-- =========================
INSTRUCTOR
======================== --}}
<section id="instructor" class="py-20 bg-white">
  <div class="max-w-5xl mx-auto px-6 grid md:grid-cols-2 gap-10 items-center">
    <div class="animate-fade-in">
      <video controls autoplay loop muted src="{{ asset('img/LifeHealer/user_video_1.mp4') }}" alt="Coach Kavita Jadhav"
        class="rounded-2xl shadow-2xl object-cover w-full h-80"></video>
    </div>
    <div class="space-y-6 animate-fade-in delay-200">
      <h2 class="text-3xl sm:text-4xl font-bold text-gray-800">Meet Your Unicorn Coach: Kavita Jadhav</h2>
      <p class="text-gray-600 leading-relaxed">
        Hello, I’m <strong>Kavita Jadhav</strong>, your Life Transformation Coach. I use cutting‐edge positive
        psychology, meditation, and energy healing to help you design your best life.
        <br /><br />
        My work has been featured in <em>Hindustan Times</em>, <em>Business World</em>, and <em>Business Standard</em>.
        I founded the “Rise n Shine Morning Manifestation Achievers Club” and have helped over <strong>4,000+</strong>
        men & women unlock their highest potential.
        <br /><br />
        In 2025, my mission is to empower <strong>1 million+</strong> people to live happier, healthier, and more
        successful lives.
      </p>
      <a href="#pricing"
        class="inline-block bg-gradient-to-r from-yellow-400 to-pink-500 text-black font-semibold rounded-full px-6 py-3 shadow-lg transform hover:scale-105 transition-transform duration-300">
        Claim Your Spot for ₹99
      </a>
    </div>
  </div>
</section>

{{-- =========================
TESTIMONIALS (Carousel)
======================== --}}
<section id="testimonials" class="py-20 bg-gradient-to-r from-purple-700 to-pink-600">
  <div class="max-w-5xl mx-auto px-6 text-center text-white">
    <h2 class="text-3xl sm:text-4xl font-bold mb-6 animate-fade-in">What Our Students Say</h2>
    <p class="text-gray-200 mb-12 animate-fade-in delay-200">
      Over <strong>10,000+</strong> men and women have already experienced massive success using this exact formula!
    </p>

    <div id="testimonialCarousel" class="relative">
      {{-- Slides Container --}}
      <div class="overflow-hidden rounded-2xl shadow-xl">
        @php
      $testimonials = [
        [
        'img' => 'c2oti_980_65.jpg',
        'text' => "Thank you for guiding me on my journey to financial abundance through manifestation. Kavita’s techniques realigned my mindset, and within weeks I saw transformative results in my career and personal life. This course is pure magic!",
        'author' => '– Manisha Mane'
        ],
        [
        'img' => 'qxnza_684_56.jpg',
        'text' => "Working with Life Coach Kavita transformed my relationships. Her guided meditations and energy exercises healed old wounds. I now feel connected, confident, and deeply content.",
        'author' => '– Anjali Sharma'
        ],
        [
        'img' => 'u0nzy_720_2.jpg',
        'text' => "Kavita’s compassion and expertise were a game‐changer. Her personal insights and practical strategies helped me overcome self-doubt and achieve my goals faster than I ever imagined.",
        'author' => '– Rani Singh'
        ],
      ];
    @endphp

        @foreach($testimonials as $idx => $t)
      <div
        class="carousel-item {{ $idx === 0 ? 'block' : 'hidden' }} bg-white rounded-2xl p-6 grid md:grid-cols-2 gap-6 animate-fade-in">
        <img src="{{ asset('img/LifeHealer/' . $t['img']) }}" alt="Testimonial {{ $idx + 1 }}"
        class="rounded-2xl object-contain w-full h-64" />
        <div class="text-gray-800 flex flex-col justify-center">
        <p class="italic leading-relaxed text-lg mb-4">“{{ $t['text'] }}”</p>
        <p class="font-semibold text-pink-600">{{ $t['author'] }}</p>
        </div>
      </div>
    @endforeach
      </div>

      {{-- Prev / Next Buttons --}}
      <button id="prevTestimonial"
        class="absolute top-1/2 left-4 -translate-y-1/2 bg-black/40 hover:bg-black/60 p-2 rounded-full transition-colors duration-200">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24"
          stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
      </button>
      <button id="nextTestimonial"
        class="absolute top-1/2 right-4 -translate-y-1/2 bg-black/40 hover:bg-black/60 p-2 rounded-full transition-colors duration-200">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24"
          stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
      </button>
    </div>
  </div>
</section>

{{-- =========================
CLIENT FEEDBACK SECTION
======================== --}}
<section id="client-feedback" class="py-20 bg-gray-100">
  <div class="max-w-7xl mx-auto px-6 text-center">
    <h2 class="text-3xl sm:text-4xl font-bold text-gray-800 mb-6 animate-fade-in">Client Feedback</h2>
    <p class="text-gray-600 mb-10 animate-fade-in delay-200">Real stories from our students, in their own words.</p>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
      @php
    $feedbacks = [
      ['avatar' => 'feedBack_1.jpeg'],
      ['avatar' => 'feedBack_2.jpeg'],
      ['avatar' => 'feedBack_3.jpeg'],
      ['avatar' => 'feedBack_4.jpeg'],
      ['avatar' => 'feedBack_5.jpeg'],
      ['avatar' => 'feedBack_6.jpeg']
    ];
    @endphp

      @foreach($feedbacks as $idx => $f)
      <div
      class="bg-white rounded-2xl p-6 shadow-lg transform hover:-translate-y-2 hover:shadow-2xl transition-all duration-300 animate-fade-in delay-{{ 200 * ($idx + 1) }}">
      <img style="    width: 100%;
    height: 100%;
    border-radius: 5px;" src="{{ asset('img/LifeHealer/' . $f['avatar']) }}" alt="Client Avatar {{ $idx + 1 }}"
        class="mx-auto h-20 w-20 rounded-full object-cover shadow-md" />
      </div>
    @endforeach
    </div>
  </div>
</section>


{{-- =========================
BONUSES
======================== --}}
<section id="bonuses" class="py-20 bg-gray-100">
  <div class="max-w-5xl mx-auto px-6 text-center">
    <h2 class="text-3xl sm:text-4xl font-bold text-gray-800 mb-6 animate-fade-in">Bonuses You’ll Get (Worth ₹5,000+)
    </h2>
    <p class="text-gray-600 mb-12 animate-fade-in delay-200">
      When you enroll today, you also unlock these exclusive resources at no extra cost:
    </p>

    <div class="grid sm:grid-cols-1 md:grid-cols-2 gap-10">
      @php
    $bonuses = [
      ['b_1.png', 'BONUS #1: प्रतिज्ञाकरण (Affirmation) eBook', ['वैयक्तिकृत प्रतिज्ञा (Affirmations)', 'आरोग्य प्रतिज्ञा / Health Affirmation', 'प्रेम आणि नाते प्रतिज्ञा / Love & Relationship Affirmations', 'करिअर आणि पैसा प्रतिज्ञा / Career & Money Affirmations', 'सामान्य प्रतिज्ञा / General Affirmations']],
      ['b_2.png', 'BONUS #2: कृतज्ञता (Gratitude) & उपचार संगीत/Healing Music', ['365 कृतज्ञता प्रॉम्प्ट/ 365 Gratitude Prompts', '432 Hz उपचार संगीत/ Healing Music', '528 Hz यश संगीत/ Success Music', 'WhatsApp समुदायात प्रवेश/ Access to Liked Mind Community', 'युनिक व्हिजन बोर्ड (Vision Board)']],
      ['b_3.png', 'BONUS #3: अतिरिक्त बोनस/ Additional Bonus', ['एक दिवस फ्री माइंड GYM पास/ One Day Free Mind GYM Pass', 'मॅनिफेस्टिंग झूम पार्टीमध्ये प्रवेश/ Access to Manifesting Zoom Party', 'मोफत 1:1 सल्लामसलत करण्याची संधी/ Chance to get a Free 1:1 Consultation', 'मॅनिफेस्टेशन मोबाइल अनुप्रयोग माहिती मिळवा (Access to Manifestation Mobile Applications)']],
      ['b_2.png', 'BONUS #4: Exclusive Community Access', ['Invitation to Private Telegram/Discord Group', 'Weekly Live Q&A Sessions with Kavita', 'Monthly Guest Expert Workshops', 'Direct Messaging & Peer Support']],
    ];
    @endphp

      @foreach($bonuses as $idx => $b)
      <div
      class="bg-white rounded-2xl p-6 text-left shadow-lg hover:-translate-y-2 hover:shadow-2xl transition-all duration-300 animate-fade-in delay-{{ 200 * ($idx + 1) }}">
      <div class="flex items-center space-x-4 mb-4">
        <img src="{{ asset('img/LifeHealer/' . $b[0]) }}" alt="{{ $b[1] }}" class="h-12 w-12 object-contain" />
        <h4 class="text-xl font-semibold text-pink-500">{{ $b[1] }}</h4>
      </div>
      <ul class="list-disc list-inside text-gray-600 space-y-1">
        @foreach($b[2] as $li)
      <li>{{ $li }}</li>
      @endforeach
      </ul>
      </div>
    @endforeach
    </div>
  </div>
</section>

{{-- =========================
COUNTDOWN & PRICING
======================== --}}
<section id="pricing" class="py-20 bg-gradient-to-r from-purple-700 to-pink-600">
  <div class="max-w-4xl mx-auto px-6 text-center text-white">
    <h2 class="text-3xl sm:text-4xl font-bold mb-6 animate-fade-in">Only a Few Seats Left—Act Now!</h2>
    <p class="text-gray-200 mb-8 animate-fade-in delay-200">
      Secure your spot for this life-transforming masterclass at the special launch price of <strong>₹5,000</strong>.
      This
      offer expires soon!
    </p>

    {{-- Countdown Timer --}}
    <div id="countdown"
      class="mx-auto flex flex-wrap items-center justify-center space-x-6 text-yellow-300 text-2xl font-bold mb-8">
      <div class="flex flex-col items-center animate-fade-in">
        <span id="days" class="text-5xl md:text-6xl">00</span>
        <span class="text-sm text-gray-200">Days</span>
      </div>
      <div class="flex flex-col items-center animate-fade-in delay-200">
        <span id="hours" class="text-5xl md:text-6xl">00</span>
        <span class="text-sm text-gray-200">Hours</span>
      </div>
      <div class="flex flex-col items-center animate-fade-in delay-400">
        <span id="minutes" class="text-5xl md:text-6xl">00</span>
        <span class="text-sm text-gray-200">Minutes</span>
      </div>
      <div class="flex flex-col items-center animate-fade-in delay-600">
        <span id="seconds" class="text-5xl md:text-6xl">00</span>
        <span class="text-sm text-gray-200">Seconds</span>
      </div>
    </div>

    {{-- Pricing Card --}}
    <div class="mt-12 bg-white/10 rounded-2xl p-8 md:p-12 shadow-2xl animate-fade-in delay-800">
      <h3 class="text-2xl font-semibold text-yellow-300 mb-4">Lifetime Access Masterclass</h3>
      <p class="text-gray-200 mb-6 leading-relaxed">
        Get instant access to all modules, guided meditations, downloadable resources, plus all four bonus packs—valued
        at ₹5,000+—for just <strong>₹99</strong> today.
      </p>
      <a href="https://rzp.io/i/KNhuciEeUM" target="_blank"
        class="inline-block bg-gradient-to-r from-yellow-400 to-pink-500 text-black font-semibold rounded-full px-8 py-4 shadow-lg transform hover:scale-105 transition-transform duration-300 mb-4">
        Reserve My Seat Now
      </a>
      <p class="text-gray-300 text-sm">30-Day Money-Back Guarantee &nbsp;|&nbsp; 100% Secure Checkout</p>
    </div>
  </div>
</section>

{{-- =========================
FOOTER
======================== --}}
<footer class="bg-gray-800 text-gray-400 py-12">
  <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-3 gap-8">
    <div class="animate-fade-in">
      <h3 class="text-xl font-bold text-white mb-2">LifeHealer+</h3>
      <p class="text-sm leading-relaxed">
        A premium life-healing & manifestation platform. Transform your life in just 2 hours—anytime, anywhere.
      </p>
    </div>
    <div class="animate-fade-in delay-200">
      <h4 class="font-semibold text-white mb-2">Quick Links</h4>
      <ul class="space-y-2 text-sm">
        <li><a href="#hero" class="hover:text-white transition-colors duration-200">Home</a></li>
        <li><a href="#about-course" class="hover:text-white transition-colors duration-200">About</a></li>
        <li><a href="#benefits" class="hover:text-white transition-colors duration-200">Benefits</a></li>
        <li><a href="#instructor" class="hover:text-white transition-colors duration-200">Instructor</a></li>
        <li><a href="#testimonials" class="hover:text-white transition-colors duration-200">Testimonials</a></li>
        <li><a href="#client-feedback" class="hover:text-white transition-colors duration-200">Client Feedback</a></li>
        <li><a href="#bonuses" class="hover:text-white transition-colors duration-200">Bonuses</a></li>
        <li><a href="#pricing" class="hover:text-white transition-colors duration-200">Enroll Now</a></li>
      </ul>
    </div>
    <div class="animate-fade-in delay-400">
      <h4 class="font-semibold text-white mb-2">Contact Us</h4>
      <p class="text-sm leading-relaxed">
        <strong>Phone:</strong> +91-7666692367<br />
        <strong>Email:</strong> <a href="mailto:support@lifehealerkavita.com"
          class="hover:text-white transition-colors duration-200">support@lifehealerkavita.com</a><br />
        <strong>Address:</strong> Narhe Gaon, Pune, Maharashtra
      </p>
      <div class="mt-4 flex space-x-4">
        <a href="#" class="hover:text-white transition-colors duration-200">
          <i class="fab fa-facebook fa-lg"></i>
        </a>
        <a href="#" class="hover:text-white transition-colors duration-200">
          <i class="fab fa-instagram fa-lg"></i>
        </a>
        <a href="#" class="hover:text-white transition-colors duration-200">
          <i class="fab fa-youtube fa-lg"></i>
        </a>
        <a href="#" class="hover:text-white transition-colors duration-200">
          <i class="fab fa-telegram fa-lg"></i>
        </a>
      </div>
    </div>
  </div>

  <div class="mt-8 text-center text-sm text-gray-500 animate-fade-in delay-600">
    © {{ date('Y') }} LifeHealer+. All rights reserved.
  </div>
</footer>

@include('CDN_Footer')

</div> {{-- End of main content wrapper --}}

{{-- =========================
JAVASCRIPT
======================== --}}
<script>
  // ===== “1 Day Left” Badge Calculation =====
  (function () {
    document.addEventListener('DOMContentLoaded', function () {
      // 1) Compute "end of today" (tomorrow at 00:00:00 local time)
      function getTomorrowMidnight() {
        const now = new Date();
        // Create a new Date for tomorrow at 00:00:00.000
        const t = new Date(
          now.getFullYear(),
          now.getMonth(),
          now.getDate() + 1, // next day
          0, 0, 0, 0
        );
        return t.getTime();
      }

      const daysEl = document.getElementById('days');
      const hoursEl = document.getElementById('hours');
      const minutesEl = document.getElementById('minutes');
      const secondsEl = document.getElementById('seconds');

      // If any of the countdown spans are missing, bail out
      if (!daysEl || !hoursEl || !minutesEl || !secondsEl) return;

      let targetTime = getTomorrowMidnight();

      function updateCountdown() {
        const now = Date.now();
        let distance = targetTime - now;

        // If we've passed midnight, recompute for the next day
        if (distance <= 0) {
          // Zero everything and stop updating
          daysEl.textContent = '00';
          hoursEl.textContent = '00';
          minutesEl.textContent = '00';
          secondsEl.textContent = '00';
          clearInterval(timerInterval);
          return;
        }

        // Compute days/hours/minutes/seconds
        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        daysEl.textContent = String(days).padStart(2, '0');
        hoursEl.textContent = String(hours).padStart(2, '0');
        minutesEl.textContent = String(minutes).padStart(2, '0');
        secondsEl.textContent = String(seconds).padStart(2, '0');
      }

      // Run immediately, then every second
      updateCountdown();
      const timerInterval = setInterval(updateCountdown, 1000);
    });
  })();

  // ===== Testimonial Carousel (jQuery) =====
  $(document).ready(function () {
    let currentIndex = 0;
    const items = $('.carousel-item');
    const totalItems = items.length;

    function showItem(index) {
      items.removeClass('block').addClass('hidden');
      items.eq(index).removeClass('hidden').addClass('block');
    }

    $('#nextTestimonial').click(function () {
      currentIndex = (currentIndex + 1) % totalItems;
      showItem(currentIndex);
    });

    $('#prevTestimonial').click(function () {
      currentIndex = (currentIndex - 1 + totalItems) % totalItems;
      showItem(currentIndex);
    });

    // Auto-rotate every 5 seconds
    setInterval(function () {
      currentIndex = (currentIndex + 1) % totalItems;
      showItem(currentIndex);
    }, 5000);
  });

  // ===== Countdown Timer =====
  (function () {
    const eventDate = new Date('2025-01-01T00:00:00').getTime(); // Change as needed
    const daysEl = document.getElementById('days');
    const hoursEl = document.getElementById('hours');
    const minutesEl = document.getElementById('minutes');
    const secondsEl = document.getElementById('seconds');

    function updateCountdown() {
      const now = new Date().getTime();
      const distance = eventDate - now;
      if (distance < 0) {
        daysEl.textContent = '00';
        hoursEl.textContent = '00';
        minutesEl.textContent = '00';
        secondsEl.textContent = '00';
        clearInterval(timerInterval);
        return;
      }
      const days = Math.floor(distance / (1000 * 60 * 60 * 24));
      const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
      const seconds = Math.floor((distance % (1000 * 60)) / 1000);

      daysEl.textContent = String(days).padStart(2, '0');
      hoursEl.textContent = String(hours).padStart(2, '0');
      minutesEl.textContent = String(minutes).padStart(2, '0');
      secondsEl.textContent = String(seconds).padStart(2, '0');
    }

    const timerInterval = setInterval(updateCountdown, 1000);
    updateCountdown();
  })();
</script>