import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FaqService } from 'src/app/common/services/faq/faq.service';
import { FaqRoutingModule } from './faq-routing.module';
import { FaqScreenComponent } from './faq-screen/faq-screen.component';

@NgModule({
  declarations: [FaqScreenComponent],
  imports: [
    FaqRoutingModule,
    CommonModule
  ],
  providers: [FaqService]
})
export class FaqModule { }
