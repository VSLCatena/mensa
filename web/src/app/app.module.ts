import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { HomeModule } from './home/home.module';
import { HttpClientModule } from '@angular/common/http';
import { NavbarComponent } from './common/components/navbar/navbar.component';
import { MensaCreationModule } from './mensa-creation/mensa-creation.module';

@NgModule({
	declarations: [AppComponent, NavbarComponent],
	imports: [BrowserModule, AppRoutingModule, HttpClientModule, HomeModule, MensaCreationModule],
	providers: [],
	bootstrap: [AppComponent]
})
export class AppModule {}
