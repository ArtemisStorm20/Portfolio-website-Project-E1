<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="{{ app()->getLocale() === 'nl' ? 'Portfolio van Amy, software developer.' : 'Portfolio of Amy, software developer.' }}">
        <title>Amy Software | {{ app()->getLocale() === 'nl' ? 'Creatieve software developer' : 'Creative software developer' }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        <div class="page-shell">
            <div class="utility-bar"><span>AMY SOFTWARE STUDIO</span><span>{{ app()->getLocale() === 'nl' ? 'Gemaakt met Liefde' : 'Assembled with Love' }}</span><span class="utility-social">f&nbsp;&nbsp;in&nbsp;&nbsp;p</span></div>
            <header class="site-header">
                <a class="brand" href="{{ route('home') }}" aria-label="Amy Software home"><span class="brand-script">Amy</span><span class="brand-subtitle">software<br>development</span></a>
                <div class="language-switcher" aria-label="Language switcher"><a class="{{ app()->getLocale() === 'nl' ? 'is-active' : '' }}" href="{{ route('language.switch', 'nl') }}">NL</a><span>/</span><a class="{{ app()->getLocale() === 'en' ? 'is-active' : '' }}" href="{{ route('language.switch', 'en') }}">EN</a></div>
            </header>
            <nav class="site-nav" aria-label="{{ app()->getLocale() === 'nl' ? 'Hoofdnavigatie' : 'Main navigation' }}"><a href="{{ route('home') }}">Home</a><a href="#about">{{ app()->getLocale() === 'nl' ? 'Over mij' : 'About' }}</a><a href="#work">{{ app()->getLocale() === 'nl' ? 'Portfolio' : 'Portfolio' }}</a><a href="#contact">{{ app()->getLocale() === 'nl' ? 'Contact' : 'Contact' }}</a></nav>

            <main>
                <section class="hero" aria-labelledby="hero-title">
                    <div class="hero-art"><div class="flower flower-one"></div><div class="flower flower-two"></div><div class="flower flower-three"></div><div class="leaf leaf-one"></div><div class="leaf leaf-two"></div><div class="hero-paper"><p class="hero-script">Hello, lovely!</p><h1 id="hero-title">{{ app()->getLocale() === 'nl' ? 'Ik maak digitale ideeën mooi.' : 'I make digital ideas beautiful.' }}</h1><p>{{ app()->getLocale() === 'nl' ? 'Ik ben Amy, software developer met een zwak voor elegante websites, slimme code en kleine details die een groot verschil maken.' : 'I am Amy, a software developer who loves elegant websites, smart code and small details that make a big difference.' }}</p><a class="button" href="#about">{{ app()->getLocale() === 'nl' ? 'Leer mij kennen' : 'Meet Amy' }} <span>&rarr;</span></a></div></div>
                </section>

                <section class="welcome-band"><div class="welcome-mark">AMY<br><span>SOFTWARE</span></div><div><p class="eyebrow">{{ app()->getLocale() === 'nl' ? 'Een creatieve developer' : 'A creative developer' }}</p><h2>{{ app()->getLocale() === 'nl' ? 'Websites met een zacht hart en een sterke basis.' : 'Websites with a soft heart and a strong foundation.' }}</h2></div><p>{{ app()->getLocale() === 'nl' ? 'Van frontend tot database: ik vertaal jouw verhaal naar een online plek die prettig werkt en helemaal als jou voelt.' : 'From frontend to database: I translate your story into an online space that works beautifully and feels completely like you.' }}</p></section>

                <section class="feature-links" id="work"><a class="feature feature-pink" href="#contact"><span>{{ app()->getLocale() === 'nl' ? 'Bekijk de' : 'See the' }}</span><strong>Portfolio</strong><i class="line-flower"></i></a><a class="feature feature-cream" href="#about"><span>{{ app()->getLocale() === 'nl' ? 'Lees mijn' : 'Read my' }}</span><strong>Story</strong><i class="line-spark">*</i></a><a class="feature feature-rose" href="#contact"><span>{{ app()->getLocale() === 'nl' ? 'Start een' : 'Start a' }}</span><strong>Project</strong><i class="line-heart">&hearts;</i></a></section>

                <section class="about-section" id="about"><div class="about-image"><span class="about-initial">A</span><span class="about-caption">code with<br>personality</span></div><div class="about-copy"><p class="eyebrow">01 / {{ app()->getLocale() === 'nl' ? 'Over Amy' : 'About Amy' }}</p><h2>{{ app()->getLocale() === 'nl' ? 'Hallo, ik ben Amy.' : 'Hello, I am Amy.' }}</h2><p>{{ app()->getLocale() === 'nl' ? 'Ik ben een software developer die graag techniek en creativiteit samenbrengt. Ik word enthousiast van een duidelijke gebruikerservaring, nette code en projecten waar ruimte is voor een eigen sfeer.' : 'I am a software developer who loves bringing technology and creativity together. I enjoy clear user experiences, tidy code and projects with room for a distinct sense of atmosphere.' }}</p><a class="text-link" href="#contact">{{ app()->getLocale() === 'nl' ? 'Meer over mij' : 'More about me' }} &rarr;</a></div></section>

                <section class="newsletter" id="contact"><div><p class="hero-script">Stay in touch</p><h2>{{ app()->getLocale() === 'nl' ? 'Laten we iets moois maken.' : 'Let us make something lovely.' }}</h2></div><form><label for="email">{{ app()->getLocale() === 'nl' ? 'Af en toe een update over nieuwe projecten.' : 'Occasional updates about new projects.' }}</label><div><input id="email" type="email" placeholder="Your email address"><button type="submit">{{ app()->getLocale() === 'nl' ? 'Aanmelden' : 'Join' }} &rarr;</button></div></form></section>
            </main>
            <footer class="site-footer"><span>AMY SOFTWARE STUDIO</span><span>{{ app()->getLocale() === 'nl' ? 'Gebouwd met code en liefde' : 'Built with code and love' }}</span><span>&copy; {{ date('Y') }}</span></footer>
        </div>
    </body>
</html>
