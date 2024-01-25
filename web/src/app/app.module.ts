import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { HomeModule } from './home/home.module';
import { HttpClientModule } from '@angular/common/http';
import { NavbarComponent } from './common/components/navbar/navbar.component';
import { MensaModule } from './mensa/mensa.module';

@NgModule({
	declarations: [AppComponent, NavbarComponent],
	imports: [
		BrowserModule,
		AppRoutingModule,
		HttpClientModule,
		HomeModule,
		MensaModule
	],
	providers: [],
	bootstrap: [AppComponent]
})
export class AppModule {}
