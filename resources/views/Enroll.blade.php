{{-- resources/views/enrollment.blade.php --}}
@include('CDN_Header')
@include('navbar')

{{-- =========================
ENROLLMENT FORM
======================== --}}
<section id="enrollment" class="py-20 bg-white">
  <div class="max-w-4xl mx-auto px-6 text-center">
    <h2 class="text-3xl sm:text-4xl font-bold text-gray-800 mb-6 animate-fade-in">Enroll Now</h2>
    <p class="text-gray-600 mb-8 animate-fade-in delay-200">
      Fill out the form below to secure your spot in the masterclass.
    </p>

    <div id="enroll-success" class="hidden mb-6 p-4 rounded-lg bg-green-100 text-green-800 font-semibold"></div>
    <div id="enroll-error" class="hidden mb-6 p-4 rounded-lg bg-red-100 text-red-800 font-semibold"></div>

    <form id="enrollForm" class="space-y-6 animate-fade-in delay-400">
      @csrf
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div>
          <label for="username" class="block text-left text-gray-700 font-semibold mb-2">Username</label>
          <input type="text" id="username" name="username" required
            class="border border-gray-300 rounded-lg p-3 w-full focus:outline-none focus:ring-2 focus:ring-purple-500" />
        </div>
        <div>
          <label for="full_name" class="block text-left text-gray-700 font-semibold mb-2">Full Name</label>
          <input type="text" id="full_name" name="full_name" required
            class="border border-gray-300 rounded-lg p-3 w-full focus:outline-none focus:ring-2 focus:ring-purple-500" />
        </div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div>
          <label for="phone" class="block text-left text-gray-700 font-semibold mb-2">Phone Number</label>
          <input type="tel" id="phone" name="phone" required
            class="border border-gray-300 rounded-lg p-3 w-full focus:outline-none focus:ring-2 focus:ring-purple-500" />
        </div>
        <div>
          <label for="email" class="block text-left text-gray-700 font-semibold mb-2">Email Address</label>
          <input type="email" id="email" name="email" required
            class="border border-gray-300 rounded-lg p-3 w-full focus:outline-none focus:ring-2 focus:ring-purple-500" />
        </div>
      </div>

      <div>
        <label for="address" class="block text-left text-gray-700 font-semibold mb-2">Address</label>
        <textarea id="address" name="address" rows="3" required
          class="border border-gray-300 rounded-lg p-3 w-full focus:outline-none focus:ring-2 focus:ring-purple-500"></textarea>
      </div>

      <div>
        <label for="additional_info" class="block text-left text-gray-700 font-semibold mb-2">Additional Information</label>
        <textarea id="additional_info" name="additional_info" rows="3"
          class="border border-gray-300 rounded-lg p-3 w-full focus:outline-none focus:ring-2 focus:ring-purple-500"></textarea>
      </div>

      <button type="submit"
        class="inline-block bg-gradient-to-r from-yellow-400 to-pink-500 text-black font-semibold rounded-full px-8 py-4 shadow-lg transform hover:scale-105 transition-transform duration-300">
        Submit Enrollment
      </button>
    </form>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $('#enrollForm').on('submit', function (e) {
    e.preventDefault();
    $('#enroll-success').hide();
    $('#enroll-error').hide();

    $.ajax({
      url: "{{ route('enrollment.submit') }}",
      method: "POST",
      data: $(this).serialize(),
      headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
      success: function (response) {
        $('#enroll-success').text('Enrollment submitted successfully!').fadeIn();
        $('#enrollForm')[0].reset();
      },
      error: function (xhr) {
        let msg = 'An error occurred. Please try again.';
        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
          msg = Object.values(xhr.responseJSON.errors).flat().join(' ');
        }
        $('#enroll-error').text(msg).fadeIn();
      }
    });
  });
</script>