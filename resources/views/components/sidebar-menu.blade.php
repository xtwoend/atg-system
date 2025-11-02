<ul class="navbar-nav pt-lg-3">
    <li class="nav-item">
        <a class="nav-link" href="/">
            <span class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler-icons.io/i/home -->
                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-device-analytics">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M3 4m0 1a1 1 0 0 1 1 -1h16a1 1 0 0 1 1 1v10a1 1 0 0 1 -1 1h-16a1 1 0 0 1 -1 -1z" />
                    <path d="M7 20l10 0" />
                    <path d="M9 16l0 4" />
                    <path d="M15 16l0 4" />
                    <path d="M8 12l3 -3l2 2l3 -3" />
                </svg>
            </span>
            <span class="nav-link-title">
                Dashboard
            </span>
        </a>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="false">
            <span class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler-icons.io/i/home -->
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-database-leak">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M4 6c0 1.657 3.582 3 8 3s8 -1.343 8 -3s-3.582 -3 -8 -3s-8 1.343 -8 3" />
                    <path d="M4 6v12c0 1.657 3.582 3 8 3s8 -1.343 8 -3v-12" />
                    <path d="M4 15a2.4 2.4 0 0 0 2 -1a2.4 2.4 0 0 1 2 -1a2.4 2.4 0 0 1 2 1a2.4 2.4 0 0 0 2 1a2.4 2.4 0 0 0 2 -1a2.4 2.4 0 0 1 2 -1a2.4 2.4 0 0 1 2 1a2.4 2.4 0 0 0 2 1" />
                </svg>
            </span>
            <span class="nav-link-title">
                ATG Report
            </span>
        </a>
        <div class="dropdown-menu">
            <div class="dropdown-menu-columns">
                <div class="dropdown-menu-column">
                    @foreach($atgs as $atg)
                    <div class="dropend">
                        <a class="dropdown-item dropdown-toggle" 
                            href="#sidebar-authentication" 
                            data-bs-toggle="dropdown"
                            data-bs-auto-close="false" 
                            role="button" 
                            aria-expanded="false">
                            {{ $atg->name }}
                        </a>
                        <div class="dropdown-menu">
                            <a href="{{ route('atg', $atg->id) }}" class="dropdown-item">
                               Overview 
                            </a>
                            <a href="{{ route('atg.report', $atg->id) }}" class="dropdown-item">
                                Report
                            </a>
                            @if(env('DISCHARGE_ENABLE', false))
                            <a href="{{ route('atg.discharge', $atg->id) }}" class="dropdown-item">
                                Discharge
                            </a>
                            @endif
                            <a href="{{ route('atg.trend', $atg->id) }}" class="dropdown-item">
                                Trend Chart
                            </a>
                            <a href="{{ route('atg.logger', $atg->id) }}" class="dropdown-item">
                                Data Logger 
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="false">
            <span class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler-icons.io/i/home -->
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-devices">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M13 9a1 1 0 0 1 1 -1h6a1 1 0 0 1 1 1v10a1 1 0 0 1 -1 1h-6a1 1 0 0 1 -1 -1v-10z" />
                    <path d="M18 8v-3a1 1 0 0 0 -1 -1h-13a1 1 0 0 0 -1 1v12a1 1 0 0 0 1 1h9" />
                    <path d="M16 9h2" />
                </svg>
            </span>
            <span class="nav-link-title">
                Equipments
            </span>
        </a>
        <div class="dropdown-menu">
            <div class="dropdown-menu-columns">
                <div class="dropdown-menu-column">
                    <a class="dropdown-item" href="{{ route('devices.index') }}">
                        Device
                    </a>
                </div>
            </div>
        </div>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="false">
            <span class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler-icons.io/i/home -->
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-database-cog">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M4 6c0 1.657 3.582 3 8 3s8 -1.343 8 -3s-3.582 -3 -8 -3s-8 1.343 -8 3" />
                    <path d="M4 6v6c0 1.657 3.582 3 8 3c.21 0 .42 -.003 .626 -.01" />
                    <path d="M20 11.5v-5.5" />
                    <path d="M4 12v6c0 1.657 3.582 3 8 3" />
                    <path d="M19.001 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                    <path d="M19.001 15.5v1.5" />
                    <path d="M19.001 21v1.5" />
                    <path d="M22.032 17.25l-1.299 .75" />
                    <path d="M17.27 20l-1.3 .75" />
                    <path d="M15.97 17.25l1.3 .75" />
                    <path d="M20.733 20l1.3 .75" />
                </svg>
            </span>
            <span class="nav-link-title">
                ATG Config
            </span>
        </a>
        <div class="dropdown-menu">
            <div class="dropdown-menu-columns">
                <div class="dropdown-menu-column">
                    <a class="dropdown-item" href="{{ route('atgsetting.tank.index') }}">
                        Storage Tank
                    </a>
                    <a class="dropdown-item" href="{{ route('atgsetting.table-volume.index') }}">
                        Volume Table 
                    </a>
                    <a class="dropdown-item" href="{{ route('atgsetting.table-density.index') }}">
                        Density Table
                    </a>
                </div>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('connections.mqtt.index') }}">
            <span class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler-icons.io/i/home -->
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M10.657 23.994h-9.45A1.21 1.21 0 0 1 0 22.788v-9.18h.071c5.784 0 10.504 4.65 10.586 10.386m7.606 0h-4.045C14.135 16.246 7.795 9.977 0 9.942V6.038h.071c9.983 0 18.121 8.044 18.192 17.956m4.53 0h-.97C21.754 12.071 11.995 2.407 0 2.372v-1.16C0 .55.544.006 1.207.006h7.64C15.733 2.49 21.257 7.789 24 14.508v8.291c0 .663-.544 1.195-1.207 1.195M16.713.006h6.092A1.19 1.19 0 0 1 24 1.2v5.914c-.91-1.242-2.046-2.65-3.158-3.762C19.588 2.11 18.122.987 16.714.005Z"/></svg>
            </span>
            <span class="nav-link-title">
                MQTT Connection
            </span>
        </a>
    </li>
    @if(Auth::user()->role == 'admin')
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="false">
            <span class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler-icons.io/i/package -->
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-adjustments-alt">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M4 8h4v4h-4z" />
                    <path d="M6 4l0 4" />
                    <path d="M6 12l0 8" />
                    <path d="M10 14h4v4h-4z" />
                    <path d="M12 4l0 10" />
                    <path d="M12 18l0 2" />
                    <path d="M16 5h4v4h-4z" />
                    <path d="M18 4l0 1" />
                    <path d="M18 9l0 11" />
                </svg>
            </span>
            <span class="nav-link-title">
            Settings
            </span>
        </a>
        <div class="dropdown-menu">
            <div class="dropdown-menu-columns">
                <div class="dropdown-menu-column">
                    <a class="dropdown-item" href="{{ route('client.index') }}">
                        Clients
                    </a>
                    <a class="dropdown-item" href="{{ route('users.index') }}">
                        Users
                    </a>
                </div>
            </div>
        </div>
    </li>
    @endif
</ul>