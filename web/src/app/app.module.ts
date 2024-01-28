import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { HomeModule } from './modules/home/home.module';
import { HttpClientModule } from '@angular/common/http';
import { NavbarComponent } from './common/components/navbar/navbar.component';
import { MensaModule } from './modules/mensa/mensa.module';
import { FaqModule } from './modules/faq/faq.module';

@NgModule({
	declarations: [AppComponent, NavbarComponent],
	imports: [
		BrowserModule,
		BrowserAnimationsModule,
		AppRoutingModule,
		HttpClientModule,
		HomeModule,
		MensaModule,
		FaqModule
	],
	providers: [],
	bootstrap: [AppComponent]
})
export class AppModule {}
