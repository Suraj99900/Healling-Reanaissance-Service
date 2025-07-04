{{-- resources/views/landing.blade.php --}}
@include('CDN_Header')
@include('navbar')

{{-- Tiny custom CSS for fade-in + some extra flair --}}
<style>
  @keyframes fade-in {
    from {
      opacity: 0;
      transform: translateY(10px);
    }

    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  .animate-fade-in {
    animation: fade-in 0.8s ease-out forwards;
    opacity: 0;
  }

  .delay-200 {
    animation-delay: 0.2s;
  }

  .delay-400 {
    animation-delay: 0.4s;
  }

  .delay-600 {
    animation-delay: 0.6s;
  }

  .delay-800 {
    animation-delay: 0.8s;
  }

  .delay-1000 {
    animation-delay: 1.0s;
  }

  .delay-1200 {
    animation-delay: 1.2s;
  }

  /* Health Icon Pulsing */
  .pulse-icon {
    animation: pulse 2s infinite;
  }

  @keyframes pulse {
    0% {
      transform: scale(1);
    }

    50% {
      transform: scale(1.1);
    }

    100% {
      transform: scale(1);
    }
  }
</style>

{{-- =========================
HERO SECTION
======================== --}}
<section id="hero" class="relative min-h-[80vh] sm:min-h-screen bg-cover bg-center overflow-hidden flex items-center"
  style="background-image: url('{{ asset('img/LifeHealer/user1.png') }}');">
  {{-- Dark overlay --}}
  <div class="absolute inset-0 bg-black/70"></div>

  <div class="relative z-10 flex flex-col justify-center w-full h-full max-w-3xl md:max-w-4xl mx-auto px-4 sm:px-6 space-y-4 sm:space-y-6 py-12 sm:py-0">
    <h1
      class="text-2xl xs:text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-extrabold text-white leading-tight drop-shadow-lg animate-fade-in delay-200">
      Welcome to <br class="hidden xs:block" /> Kvita's Healling Renaissance
    </h1>

    {{-- Headline from PDF --}}
    <h2
      class="text-lg xs:text-xl sm:text-2xl md:text-3xl lg:text-4xl font-extrabold text-white leading-tight drop-shadow-lg animate-fade-in delay-200">
      Transform your life with Manifestation<br>
      To Recreate the Life of your Dreams<br>
    </h2>

    <p class="mt-2 sm:mt-4 text-base xs:text-lg sm:text-xl text-gray-300 max-w-xl animate-fade-in delay-400">
      Do you feel stuck in the same cycle? <br class="hidden sm:block" /> Are you ready to create a life full of purpose, abundance, and inner
      peace?
    </p>
    <p class="mt-2 sm:mt-4 text-base xs:text-lg sm:text-xl text-gray-200 max-w-xl animate-fade-in delay-400">
      At Kvita's Healling Renaissance, we believe that your thoughts shape your reality. Through the power of
      manifestation, positive thinking, and daily affirmations, you can rewire your mindset, shift your energy, and
      attract the life you truly desire.
    </p>

    <div
      class="mt-6 sm:mt-8 flex flex-col sm:flex-row items-start sm:items-center space-y-3 sm:space-y-0 sm:space-x-4 animate-fade-in delay-600">
      <a href="{{ url('enroll') }}"
        class="inline-block bg-gradient-to-r from-yellow-400 to-pink-500 text-black font-semibold rounded-full px-6 py-3 sm:px-8 sm:py-4 shadow-lg transform hover:scale-105 transition-transform duration-300 text-base sm:text-lg">
        Enroll Now &bull;
        <span class="ml-2"><i class="fa fa-arrow-right"></i></span>
      </a>
      {{-- Dynamic “Days Left” Badge (JS will fill) --}}
      <span id="daysLeftBadge"
        class="bg-red-500 text-white font-semibold rounded-full px-4 py-2 shadow-lg animate-pulse text-xs sm:text-sm"></span>
    </div>

    <div class="mt-6 sm:mt-8 flex flex-wrap items-center space-x-4 sm:space-x-6 text-gray-300 animate-fade-in delay-800">
      <div class="flex items-center space-x-1">
        @for ($i = 0; $i < 5; $i++)
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5 text-yellow-400 animate-pulse" viewBox="0 0 20 20"
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
      <span class="text-xs sm:text-sm animate-fade-in delay-1000">4.9/5 (12,000+ Reviews)</span>
      <span class="text-xs sm:text-sm animate-fade-in delay-1200">100K+ Lives Transformed</span>
    </div>
  </div>
</section>

{{-- =========================
ABOUT THIS MASTERCLASS
======================== --}}
<section id="about-course" class="py-20 bg-gradient-to-r from-purple-700 to-pink-600">
  <div class="max-w-5xl mx-auto px-6 text-center">
    <h2 class="text-3xl sm:text-4xl font-bold text-white mb-4 animate-fade-in">About This Masterclass</h2>

    <p class="mt-4 text-lg sm:text-xl text-gray-200 leading-relaxed animate-fade-in delay-200">
      Revealing the “4D Success Framework” that will help you get unstuck from an unfulfilling corporate job, restart
      your career at any age, and find your true calling, happiness &amp; peace! We combine science-backed techniques
      (visualization, affirmations, neuroscience) with spiritual practices (meditation, energy alignment, intention
      setting) to activate the Law of Attraction and the Law of Assumption in your favor.
    </p>

    <div class="mt-12 flex justify-center">
      <a href="#benefits"
        class="inline-block bg-white text-pink-600 font-semibold rounded-full px-8 py-4 shadow-lg transform hover:scale-105 transition-transform duration-300 animate-fade-in delay-600">
        Let’s Make It Happen
      </a>
    </div>
  </div>
</section>

{{-- =========================
CORE MODULES
======================== --}}
<section id="core-modules" class="py-20 bg-white">
  <div class="max-w-5xl mx-auto px-6">
    <h2 class="text-3xl sm:text-4xl font-bold text-gray-800 text-center mb-8 animate-fade-in">Core Modules</h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
      @php
    $pdfModules = [
      ['image1.png', 'Emotional Alignment'],
      ['image2.png', 'Reprogramming Subconscious Mind'],
      ['image3.png', 'Career & Purpose'],
      ['image4.png', 'The Law of Attraction Decoded'],
      ['image5.png', 'Money Beliefs'],
      ['image6.png', 'Mindset Reset'],
      ['image7.png', 'Manifestation Accelerator'],
      ['image8.png', 'Energy Alignment']
    ];
  @endphp

      @foreach($pdfModules as $idx => $m)
      <div
      class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-2xl p-6 text-center shadow-lg transform hover:-translate-y-2 hover:shadow-2xl transition-all duration-300 animate-fade-in delay-{{ 200 * ($idx + 1) }}">
      <img src="{{ asset('img/CoreImg/' . $m[0]) }}" alt="{{ $m[1] }}"
        class="mx-auto mb-4 h-24 w-24 object-contain rounded-xl shadow-md border border-pink-100" />
      <h3 class="text-xl font-semibold text-pink-600 mb-2">{{ $m[1] }}</h3>
      <p class="text-gray-600 text-sm">
        Dive deep into <strong>{{ strtolower($m[1]) }}</strong> techniques to elevate your manifestation journey.
      </p>
      </div>
    @endforeach
    </div>
  </div>
</section>

{{-- =========================
WHY YOU SHOULD JOIN
======================== --}}
<section id="benefits" class="py-20 bg-gray-100">
  <div class="max-w-7xl mx-auto px-6">
    <h2 class="text-3xl sm:text-4xl font-bold text-gray-800 text-center animate-fade-in">Why You Should Join</h2>
    <p class="mt-4 text-lg text-gray-600 text-center max-w-3xl mx-auto animate-fade-in delay-200">
      Over <strong>100,000</strong> men and women have used this exact formula to transform their careers, health,
      relationships, and inner peace. Here’s what you’ll gain:
    </p>

    <p class="mt-4 text-lg text-gray-600 text-center max-w-3xl mx-auto animate-fade-in delay-200">
      Break Free from Burnout<br>
      Eliminate stress, anxiety, and overwhelm—step into a state of calm, clarity, and unstoppable energy.
    </p>

    <div class="mt-12 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-10">
      @php
    $pdfBenefits = [
      [
      'title' => 'Break Free from Burnout',
      'desc' => 'Eliminate stress, anxiety, and overwhelm — step into calm, clarity, and unstoppable energy.',
      'icon' => 'M5 13l4 4L19 7'
      ],
      [
      'title' => 'Skyrocket Your Career',
      'desc' => 'Attract promotions, salary hikes, or dream‐job offers — no matter your age or background.',
      'icon' => 'M3 10h18M7 6h10M12 2v20'
      ],
      [
      'title' => 'Deep Healing & Peace',
      'desc' => 'Use scientifically proven meditation and energy practices to heal body, mind, and spirit—instantly.',
      'icon' => 'M3 7l5 5-5 5M19 7l-5 5 5 5'
      ],
      [
      'title' => 'Manifest Anything',
      'desc' => 'Program your subconscious to attract wealth, love, health, and happiness — live on your terms.',
      'icon' => 'M17 20h5V4H2v16h5m10 0V4M7 8h.01M7 12h.01M7 16h.01'
      ],
      [
      'title' => 'Video Access',
      'desc' => 'Rewatch all course content anytime — plus receive all future updates at no extra cost.',
      'icon' => 'M12 8c-2.21 0-4 1.79-4 4 0 1.657 1.007 3.066 2.4 3.584L12 22l1.6-6.416C14.993 15.066 16 13.657 16 12c0-2.21-1.79-4-4-4z'
      ],
      [
      'title' => '24/7 Support',
      'desc' => 'Our dedicated team is available around the clock to answer questions and guide you.',
      'icon' => 'M3 10h18M7 6h10M12 2v20'
      ],
    ];
  @endphp

      @foreach($pdfBenefits as $idx => $ben)
      <div
      class="bg-white rounded-2xl p-6 text-center shadow-lg hover:-translate-y-2 hover:shadow-2xl transition-all duration-300 animate-fade-in delay-{{ 200 * ($idx + 1) }}">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-pink-500 mb-4" fill="none"
        viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $ben['icon'] }}" />
      </svg>
      <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ $ben['title'] }}</h3>
      <p class="text-gray-600">{{ $ben['desc'] }}</p>
      </div>
    @endforeach
    </div>
  </div>
</section>

{{-- =========================
HEALTH & WELLNESS HIGHLIGHTS (NEW SECTION)
======================== --}}
<section id="health" class="py-20 bg-white">
  <div class="max-w-7xl mx-auto px-6 text-center">
    <h2 class="text-3xl sm:text-4xl font-bold text-gray-800 mb-6 animate-fade-in">Health & Wellness Highlights</h2>
    <p class="text-lg text-gray-600 max-w-3xl mx-auto leading-relaxed animate-fade-in delay-200">
      In addition to powerful manifestation techniques, this masterclass brings you holistic health benefits:
      improved mental well-being, stress reduction, and energy alignment for a stronger, more vibrant you.
    </p>

    <div class="mt-12 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-10">
      {{-- Mental Well-being --}}
      <div
        class="bg-gray-50 rounded-2xl p-6 shadow-lg hover:-translate-y-2 hover:shadow-2xl transition-all duration-300 animate-fade-in delay-400">
        <div class="flex flex-col items-center">
          <img src="{{ asset('img/LifeHealer/mental-img/image1.png') }}" alt="Mental Well-being" class="w-40 h-40 hover:shadow-2x1 rounded-2xl mb-4 object-contain" />
          <h3 class="text-xl font-semibold text-gray-800 mb-2">Mental Well-being</h3>
          <p class="text-gray-600">
            Learn mindfulness & meditation to calm a racing mind, reduce anxiety, and boost clarity.
          </p>
        </div>
      </div>

      {{-- Stress Reduction --}}
      <div
        class="bg-gray-50 rounded-2xl p-6 shadow-lg hover:-translate-y-2 hover:shadow-2xl transition-all duration-300 animate-fade-in delay-600">
        <div class="flex flex-col items-center">
          <img src="{{ asset('img/LifeHealer/mental-img/image2.png') }}" alt="Stress Reduction" class="w-40 h-40 hover:shadow-2x1 rounded-2xl mb-4 object-contain" />
          <h3 class="text-xl font-semibold text-gray-800 mb-2">Stress Reduction</h3>
          <p class="text-gray-600">
            Master breathing exercises & guided visualizations that release tension and restore balance.
          </p>
        </div>
      </div>

      {{-- Energy Alignment --}}
      <div
        class="bg-gray-50 rounded-2xl p-6 shadow-lg hover:-translate-y-2 hover:shadow-2xl transition-all duration-300 animate-fade-in delay-800">
        <div class="flex flex-col items-center">
          <img src="{{ asset('img/LifeHealer/mental-img/image3.png') }}" alt="Energy Alignment" class="w-40 h-40 hover:shadow-2x1 rounded-2xl mb-4 object-contain" />
          <h3 class="text-xl font-semibold text-gray-800 mb-2">Energy Alignment</h3>
          <p class="text-gray-600">
            Align your chakras & life force through simple energy practices to feel more vitality every day.
          </p>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- =========================
INSTRUCTOR SECTION
======================== --}}
<section id="instructor" class="py-20 bg-gradient-to-r from-purple-700 to-pink-600">
  <div class="max-w-5xl mx-auto px-6 grid md:grid-cols-2 gap-10 items-center">
    <div class="animate-fade-in">
      <video controls autoplay loop muted src="{{ asset('img/LifeHealer/video/main-kvita.mp4') }}" alt="Coach Kvita Video"
        class="rounded-2xl shadow-2xl object-cover w-full h-80"></video>
    </div>
    <div class="space-y-6 animate-fade-in delay-200">
      <h2 class="text-3xl sm:text-4xl font-bold text-white">Meet Your Unicorn Coach: Kvita Jadhav</h2>
      <p class="text-gray-200 leading-relaxed">
        Hello, I’m <strong>Kvita Jadhav</strong>, your Life Transformation Coach. I use cutting‐edge positive
        psychology, meditation, and energy healing to help you design your best life.
        <br /><br />
        My work has been featured in <em>Hindustan Times</em>, <em>Business World</em>, and <em>Business Standard</em>.
        I founded the “Rise n Shine Morning Manifestation Achievers Club” and have helped over <strong>4,000+</strong>
        men &amp; women unlock their highest potential.
        <br /><br />
        In 2025, my mission is to empower <strong>1 million+</strong> people to live happier, healthier, and more
        successful lives.
      </p>
      <a href="https://rzp.io/rzp/Ch1XJWuD" target="_blank"
        class="inline-block bg-gradient-to-r from-yellow-400 to-pink-500 text-black font-semibold rounded-full px-6 py-3 shadow-lg transform hover:scale-105 transition-transform duration-300">
        Claim Your Spot for ₹20,000
      </a>
    </div>
  </div>
</section>


{{-- =========================
REWARDS & RECOGNITION
======================== --}}
<section id="rewards" class="py-20 bg-gradient-to-br from-yellow-100 to-pink-100">
  <div class="max-w-4xl mx-auto px-6 text-center">
    <h2 class="text-3xl sm:text-4xl font-bold text-pink-700 mb-8 animate-fade-in">Kvita's Rewards & Recognition</h2>
    <p class="text-lg text-gray-700 mb-10 animate-fade-in delay-200">
      Celebrating the achievements and milestones of Kvita's inspiring journey.
    </p>
    @php
      $rewards = [
        '1 (1).jpeg',
        '1 (2).jpeg',
        '1 (3).jpeg',
        '1 (4).jpeg',
        '1 (5).jpeg',
        '1 (6).jpeg',
      ];
    @endphp
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
      @foreach($rewards as $idx => $img)
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden hover:scale-105 transform transition-all duration-300 animate-fade-in delay-{{ 100 * ($idx + 1) }}">
          <img src="{{ asset('img/LifeHealer/Reward/' . $img) }}" alt="Reward {{ $idx + 1 }}"
            class="w-full h-72 object-cover" />
        </div>
      @endforeach
    </div>
  </div>
</section>


{{-- =========================
SUCCESS STORIES FROM PAST PARTICIPANTS
======================== --}}

<section id="success-stories" class="py-24 bg-gradient-to-b from-gray-50 to-white">
  <div class="max-w-7xl mx-auto px-4 text-center">
    <h2 class="text-4xl font-extrabold text-gray-900 mb-6 animate-fade-in">Success Stories from Past Participants</h2>
    <p class="text-lg text-gray-600 max-w-3xl mx-auto mb-12 leading-relaxed animate-fade-in delay-200">
      The techniques shared in this workshop are simple and effective for everyone.<br>
      Over 20,000 participants have transformed their lives using these proven manifestation methods.<br>
      Even if you have no prior knowledge about manifestation, that’s perfectly okay—start from where you are and
      experience the results.<br>
      No special tools or rituals are required—just an open mind and a willingness to learn are enough.
    </p>

    @php
    $successVideos = [
      '1.mp4',
      '2.mp4',
      '3.mp4',
      '4.mp4',
      '5.mp4',
      '6.mp4',
      '7.mp4',
      '8.mp4',
      '9.mp4',
      '10.mp4',
      '11.mp4',
      '12.mp4',
    ];
    @endphp

    <div class="swiper mySwiper">
      <div class="swiper-wrapper">
        @foreach($successVideos as $idx => $video)
        <div class="swiper-slide flex justify-center">
          <div
            class="rounded-3xl bg-white/70 backdrop-blur-lg shadow-xl p-2 w-full max-w-sm transform transition-transform hover:scale-105 duration-300 ease-in-out">
            <video 
              src="{{ asset('img/LifeHealer/video/' . $video) }}" 
              autoplay loop muted playsinline controls
              class="rounded-2xl w-full h-64 object-cover success-story-video"
              poster="{{ asset('img/LifeHealer/success/poster_' . ($idx + 1) . '.jpg') }}"
              controlslist="nodownload noremoteplayback"
              style="background: #000"
            ></video>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </div>

  <!-- Swiper Configuration & Fullscreen JS -->
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      new Swiper('.mySwiper', {
        slidesPerView: 1.2,
        spaceBetween: 30,
        loop: true,
        centeredSlides: true,
        speed: 500,
        autoplay: {
          delay: 3000,
          disableOnInteraction: false,
        },
        breakpoints: {
          640: { slidesPerView: 1.5 },
          768: { slidesPerView: 2 },
          1024: { slidesPerView: 3 },
          1280: { slidesPerView: 4 },
        }
      });

      // Enable fullscreen on double-click for all videos in this section
      document.querySelectorAll('.success-story-video').forEach(function(video) {
        video.addEventListener('dblclick', function(e) {
          if (video.requestFullscreen) {
            video.requestFullscreen();
          } else if (video.webkitRequestFullscreen) {
            video.webkitRequestFullscreen();
          } else if (video.msRequestFullscreen) {
            video.msRequestFullscreen();
          }
        });
      });
    });
  </script>
</section>



{{-- =========================
REAL RESULTS, REAL STORIES
======================== --}}
<section id="real-results" class="py-24 bg-gradient-to-b from-white to-gray-100">
  <div class="max-w-7xl mx-auto px-6 text-center">
    <h2 class="text-4xl font-extrabold text-gray-900 mb-6 animate-fade-in">Real Results, Real Stories</h2>
    <p class="text-lg text-gray-600 mb-12 animate-fade-in delay-200">• Trained over 20,000 people</p>

    @php
    $resultImages = [
      'result_1.png',
      'result_2.png',
      'result_3.png',
      'result_4.png',
      'result_5.png',
      'result_6.png',
      'result_7.png',
      'result_8.png',
      'result_9.png',
    ];
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-10">
      @foreach($resultImages as $idx => $img)
      <div
      class="bg-white/70 backdrop-blur-lg rounded-3xl overflow-hidden shadow-xl transform transition-transform hover:scale-105 duration-300 animate-fade-in delay-{{ 100 * ($idx + 1) }}">
      <div class="aspect-w-1 aspect-h-1">
        <img src="{{ asset('img/LifeHealer/results/' . $img) }}" alt="Real Result {{ $idx + 1 }}"
        class="w-full h-full object-cover rounded-3xl transition-transform duration-300 hover:scale-105" />
      </div>
      </div>
    @endforeach
    </div>
  </div>
</section>


{{-- =========================
TESTIMONIALS CAROUSEL
======================== --}}
<section id="testimonials" class="py-20 bg-white">
  <div class="max-w-5xl mx-auto px-6 text-center text-gray-800">
    <h2 class="text-3xl sm:text-4xl font-bold mb-6 animate-fade-in">What Our Students Say</h2>
    <p class="text-gray-600 mb-12 animate-fade-in delay-200">
      Over <strong>10,000+</strong> men and women have already experienced massive success using this formula!
    </p>

    <div id="testimonialCarousel" class="relative">
      {{-- Slides Container --}}
      <div class="overflow-hidden rounded-2xl shadow-lg">
        @php
      $testimonials = [
        [
        'img' => 'c2oti_980_65.jpg',
        'text' => "Thank you for guiding me on my journey to financial abundance through manifestation. Kvita’s techniques realigned my mindset, and within weeks I saw transformative results in my career and personal life. This course is pure magic!",
        'author' => '– Manisha Mane'
        ],
        [
        'img' => 'qxnza_684_56.jpg',
        'text' => "Working with Life Coach Kvita transformed my relationships. Her guided meditations and energy exercises healed old wounds. I now feel connected, confident, and deeply content.",
        'author' => '– Anjali Sharma'
        ],
        [
        'img' => 'u0nzy_720_2.jpg',
        'text' => "Kvita’s compassion and expertise were a game‐changer. Her personal insights and practical strategies helped me overcome self-doubt and achieve my goals faster than I ever imagined.",
        'author' => '– Rani Singh'
        ],
      ];
    @endphp

        @foreach($testimonials as $idx => $t)
      <div
        class="carousel-item {{ $idx === 0 ? 'block' : 'hidden' }} bg-gray-50 rounded-2xl p-6 grid md:grid-cols-2 gap-6 animate-fade-in">
        <img src="{{ asset('img/LifeHealer/' . $t['img']) }}" alt="Testimonial {{ $idx + 1 }}"
        class="rounded-2xl object-contain w-full h-64 md:h-48" />
        <div class="flex flex-col justify-center">
        <p class="italic leading-relaxed text-lg mb-4 text-gray-700">“{{ $t['text'] }}”</p>
        <p class="font-semibold text-purple-600">{{ $t['author'] }}</p>
        </div>
      </div>
    @endforeach
      </div>

      {{-- Prev / Next Buttons --}}
      <button id="prevTestimonial"
        class="absolute top-1/2 left-4 -translate-y-1/2 bg-purple-500/40 hover:bg-purple-600/60 p-2 rounded-full transition-colors duration-200">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24"
          stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
      </button>
      <button id="nextTestimonial"
        class="absolute top-1/2 right-4 -translate-y-1/2 bg-purple-500/40 hover:bg-purple-600/60 p-2 rounded-full transition-colors duration-200">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24"
          stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
      </button>
    </div>
  </div>
</section>

<!-- {{-- =========================
CLIENT FEEDBACK
======================== --}}
<section id="client-feedback" class="py-20 bg-gray-100">
  <div class="max-w-7xl mx-auto px-6 text-center">
    <h2 class="text-3xl sm:text-4xl font-bold text-gray-800 mb-6 animate-fade-in">Client Feedback</h2>
    <p class="text-gray-600 mb-10 animate-fade-in delay-200">Real stories from our students, in their own words.</p>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
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
      <img src="{{ asset('img/LifeHealer/' . $f['avatar']) }}" alt="Client Avatar {{ $idx + 1 }}"
        class="mx-auto h-40 w-30 rounded-2X1 object-cover shadow-md" />
      </div>
    @endforeach
    </div>
  </div>
</section> -->

{{-- =========================
BONUSES
======================== --}}
<section id="bonuses" class="py-20 bg-gradient-to-r from-purple-700 to-pink-600">
  <div class="max-w-6xl mx-auto px-6 text-center text-white">
    <h2 class="text-3xl sm:text-4xl font-bold mb-4 animate-fade-in">Bonuses If You Register Now</h2>
    <p class="text-xl font-semibold text-yellow-300 mb-2 animate-fade-in delay-200">Total Value ₹ 50,000</p>
    <p class="text-2xl font-bold mb-5 mt-5 animate-fade-in delay-400">
      <span class="bg-yellow-400 text-pink-700 px-6  py-3 rounded-full shadow-lg">TODAY'S OFFER PRICE: ₹20,000</span>
    </p>
    <p class="text-gray-200 mb-14 animate-fade-in delay-600">
      When you enroll today, you unlock these exclusive resources at no extra cost:
    </p>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
      @php
      $bonuses = [
        [
          'img' => 'Bounuses/image1.png',
          'title' => 'BONUS #1: One Day Free Mind GYM Pass',
          'value' => 'Value: ₹2,999/-',
          'points' => [
            'The use of gratitude to bring positive changes in life.',
            'Practical gratitude exercises for a happy and content life.',
          ]
        ],
        [
          'img' => 'Bounuses/image2.png',
          'title' => 'BONUS #2: Gratitude eBook',
          'value' => 'Value: ₹997/-',
          'points' => [
            'The use of gratitude to bring positive changes in life.',
            'Practical gratitude exercises for a happy and content life.',
          ]
        ],
        [
          'img' => 'Bounuses/image3.png',
          'title' => 'BONUS #3: Self Programming Challenge',
          'value' => 'Value: ₹1,499/-',
          'points' => [
            'Activities to reprogram your subconscious mind.',
            'Confidence-boosting challenges.',
            'A 7-day step-by-step process to achieve your goals.',
          ]
        ],
        [
          'img' => 'Bounuses/image4.png',
          'title' => 'BONUS #4: Gratitude & Healing Music',
          'value' => 'Value: ₹1,997/-',
          'points' => [
            '365 Gratitude Prompts',
            'Healing Music',
            'Success Music',
            'Access to Liked Mind Community',
            'Vision Board',
          ]
        ],
        [
          'img' => 'Bounuses/image5.png',
          'title' => 'BONUS #5: Affirmation eBook',
          'value' => 'Value: ₹999/-',
          'points' => [
            'Affirmations',
            'Health Affirmation',
            'Love & Relationship Affirmations',
            'Career & Money Affirmations',
            'General Affirmations',
          ]
        ],
        [
          'img' => 'Bounuses/image6.png',
          'title' => 'BONUS #6: Routine For Your Manifestation Journey',
          'value' => 'Value: ₹1,997/-',
          'points' => [
            'Guidance to create a positive daily routine.',
            'Practical tips to manifest consistently.',
          ]
        ],
      ];
      @endphp

      @foreach($bonuses as $idx => $b)
      <div class="bg-white/10 rounded-2xl p-6 text-left shadow-lg hover:-translate-y-2 hover:shadow-2xl transition-all duration-300 animate-fade-in delay-{{ 150 * ($idx + 1) }}">
        <img src="{{ asset('img/LifeHealer/' . $b['img']) }}" alt="{{ $b['title'] }}"
          class="w-full h-72 object-contain rounded-xl shadow-md border border-pink-100 mb-6 bg-white" />
        <h4 class="text-xl font-semibold text-yellow-300 mb-1">{{ $b['title'] }}</h4>
        <span class="block text-sm text-white/80 font-bold mb-3">{{ $b['value'] }}</span>
        <ul class="list-disc list-inside text-gray-100 space-y-1 ml-2">
          @foreach($b['points'] as $li)
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
<section id="pricing" class="py-20 bg-white">
  <div class="max-w-4xl mx-auto px-6 text-center text-gray-800">
    <h2 class="text-3xl sm:text-4xl font-bold mb-6 animate-fade-in">Only a Few Seats Left—Act Now!</h2>
    <p class="text-gray-600 mb-8 animate-fade-in delay-200">
      Secure your spot for this life-transforming masterclass at the special launch price of <strong>₹20,000</strong>.
      This offer expires soon!
    </p>

    {{-- Countdown Timer --}}
    <!-- <div id="countdown"
      class="mx-auto flex flex-wrap items-center justify-center space-x-6 text-purple-600 text-2xl font-bold mb-8">
      <div class="flex flex-col items-center animate-fade-in">
        <span id="days" class="text-5xl md:text-6xl">00</span>
        <span class="text-sm text-gray-500">Days</span>
      </div>
      <div class="flex flex-col items-center animate-fade-in delay-200">
        <span id="hours" class="text-5xl md:text-6xl">00</span>
        <span class="text-sm text-gray-500">Hours</span>
      </div>
      <div class="flex flex-col items-center animate-fade-in delay-400">
        <span id="minutes" class="text-5xl md:text-6xl">00</span>
        <span class="text-sm text-gray-500">Minutes</span>
      </div>
      <div class="flex flex-col items-center animate-fade-in delay-600">
        <span id="seconds" class="text-5xl md:text-6xl">00</span>
        <span class="text-sm text-gray-500">Seconds</span>
      </div>
    </div> -->

    {{-- Pricing Card --}}
    <div
      class="mt-12 bg-gradient-to-r from-purple-700 to-pink-500 rounded-2xl p-8 md:p-12 shadow-2xl animate-fade-in delay-800">
      <h3 class="text-2xl font-semibold text-white mb-4">Lifetime Access Masterclass</h3>
      <p class="text-white/90 mb-6 leading-relaxed">
        Get instant access to all modules, guided meditations, downloadable resources, plus all four bonus packs—
        valued at ₹20,000+—for just <strong>₹20,000</strong> today.
      </p>
      <a href="https://rzp.io/rzp/Ch1XJWuD" target="_blank"
        class="inline-block bg-yellow-400 text-black font-semibold rounded-full px-8 py-4 shadow-lg transform hover:scale-105 transition-transform duration-300 mb-4">
        Reserve My Seat Now
      </a>
      <p class="text-gray-200 text-sm">30-Day Money-Back Guarantee &nbsp;|&nbsp; 100% Secure Checkout</p>
    </div>
  </div>
</section>

{{-- =========================
FOOTER
======================== --}}
<footer class="bg-gray-800 text-gray-400 py-12">
  <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-3 gap-8">
    {{-- About --}}
    <div class="animate-fade-in">
      <h3 class="text-xl font-bold text-white mb-2">LifeHealer+</h3>
      <p class="text-sm leading-relaxed">
        A premium life-healing &amp; manifestation platform. Transform your life in just 2 hours—anytime, anywhere.
      </p>
    </div>

    {{-- Quick Links --}}
    <div class="animate-fade-in delay-200">
      <h4 class="font-semibold text-white mb-2">Quick Links</h4>
      <ul class="space-y-2 text-sm">
        <li><a href="#hero" class="hover:text-white transition-colors duration-200">Home</a></li>
        <li><a href="#about-course" class="hover:text-white transition-colors duration-200">About</a></li>
        <li><a href="#core-modules" class="hover:text-white transition-colors duration-200">Modules</a></li>
        <li><a href="#benefits" class="hover:text-white transition-colors duration-200">Benefits</a></li>
        <li><a href="#health" class="hover:text-white transition-colors duration-200">Health</a></li>
        <li><a href="#instructor" class="hover:text-white transition-colors duration-200">Instructor</a></li>
        <li><a href="#testimonials" class="hover:text-white transition-colors duration-200">Testimonials</a></li>
        <li><a href="#client-feedback" class="hover:text-white transition-colors duration-200">Client Feedback</a></li>
        <li><a href="#bonuses" class="hover:text-white transition-colors duration-200">Bonuses</a></li>
        <li><a href="#pricing" class="hover:text-white transition-colors duration-200">Enroll Now</a></li>
      </ul>
    </div>

    {{-- Contact --}}
    <div class="animate-fade-in delay-400">
      <h4 class="font-semibold text-white mb-2">Contact Us</h4>
      <p class="text-sm leading-relaxed">
        <strong>Phone:</strong> +91-7666692367<br />
        <strong>Email:</strong> <a href="mailto:support@lifehealerKvita.com"
          class="hover:text-white transition-colors duration-200">support@lifehealerKvita.com</a><br />
        <strong>Address:</strong> Narhe Gaon, Pune, Maharashtra
      </p>
      <div class="mt-4 flex space-x-4">
        <a href="#" class="hover:text-white transition-colors duration-200"><i class="fab fa-facebook fa-lg"></i></a>
        <a href="#" class="hover:text-white transition-colors duration-200"><i class="fab fa-instagram fa-lg"></i></a>
        <a href="#" class="hover:text-white transition-colors duration-200"><i class="fab fa-youtube fa-lg"></i></a>
        <a href="#" class="hover:text-white transition-colors duration-200"><i class="fab fa-telegram fa-lg"></i></a>
      </div>
    </div>
  </div>

  <div class="mt-8 text-center text-sm text-gray-500 animate-fade-in delay-600">
    © {{ date('Y') }} LifeHealer+. All rights reserved.
  </div>
</footer>

@include('CDN_Footer')

{{-- =========================
JAVASCRIPT
======================== --}}
<script>
  // ===== Dynamic “Days Left” Badge =====
  document.addEventListener('DOMContentLoaded', () => {
    const badge = document.getElementById('daysLeftBadge');
    if (!badge) return;

    function computeDaysLeft() {
      const now = new Date();
      const midnight = new Date(now.getFullYear(), now.getMonth(), now.getDate() + 1);
      const diff = midnight - now;
      return Math.ceil(diff / (1000 * 60 * 60 * 24));
    }

    function updateBadge() {
      const d = computeDaysLeft();
      badge.textContent = d + (d > 1 ? ' Days Left' : ' Day Left');
    }

    updateBadge();
    setInterval(updateBadge, 60 * 60 * 1000); // every hour
  });

  // ===== Testimonial Carousel (Vanilla JS) =====
  document.addEventListener('DOMContentLoaded', () => {
    let currentIndex = 0;
    const items = document.querySelectorAll('.carousel-item');
    const total = items.length;
    const prevBtn = document.getElementById('prevTestimonial');
    const nextBtn = document.getElementById('nextTestimonial');

    function showSlide(index) {
      items.forEach((item, i) => {
        item.classList.toggle('hidden', i !== index);
        item.classList.toggle('block', i === index);
      });
    }

    if (prevBtn && nextBtn) {
      prevBtn.addEventListener('click', () => {
        currentIndex = (currentIndex - 1 + total) % total;
        showSlide(currentIndex);
      });
      nextBtn.addEventListener('click', () => {
        currentIndex = (currentIndex + 1) % total;
        showSlide(currentIndex);
      });
      setInterval(() => {
        currentIndex = (currentIndex + 1) % total;
        showSlide(currentIndex);
      }, 5000);
    }
  });

  // ===== Countdown Timer =====
  document.addEventListener('DOMContentLoaded', () => {
    const daysEl = document.getElementById('days');
    const hoursEl = document.getElementById('hours');
    const minutesEl = document.getElementById('minutes');
    const secondsEl = document.getElementById('seconds');

    if (!daysEl || !hoursEl || !minutesEl || !secondsEl) return;

    // Set your target date here (e.g. course launch date)
    const targetDate = new Date('2025-07-01T00:00:00').getTime();

    function updateCountdown() {
      const now = Date.now();
      const distance = targetDate - now;

      if (distance < 0) {
        daysEl.textContent = '00';
        hoursEl.textContent = '00';
        minutesEl.textContent = '00';
        secondsEl.textContent = '00';
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

    updateCountdown();
    setInterval(updateCountdown, 1000);
  });
</script>